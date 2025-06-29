<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Toda;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode as SimpleQrCode;
use Endroid\QrCode\QrCode;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::with(['toda'])->get();
        return view('tricycle.driver.driver-all', compact('drivers'));
    }

    public function create()
    {
        $todas = Toda::all();
        return view('tricycle.driver.driver-create', compact('todas'));
    }

    public function addDriver(Request $request)
    {
        $request->validate([
            'driverName' => 'required|min:2|max:100',
            'contactNumber' => 'required|min:11|max:11|unique:tbl_driverinfo,driverPhoneNum',
            'plateNumber' => 'required|max:6|unique:tbl_driverinfo,plateNumber',
            'tinPlate' => [
                'required',
                'unique:tbl_driverinfo,tinPlate',
                'regex:/^[0-9]{4}$/'
            ],
            
            'todaID' => 'required'
        ]);

        Driver::create([
            'driverName' => $request->driverName,
            'driverPhoneNum' => $request->contactNumber,
            'plateNumber' => $request->plateNumber,
            'tinPlate' => $request->tinPlate,
            
            'todaID' => $request->todaID
        ]);

        return redirect()->route('drivers')->with('success', 'New Driver created successfully.');
    }

    public function driverDelete(Driver $driverID)
    {
        if ($driverID->complaints()->exists()) {
            return redirect()->route('drivers')->with('success', 'Driver cannot be deleted because there are associated complaints.');
        }

        $driverID->delete();

        return redirect()->route('drivers')->with('success', 'Driver deleted successfully.');
    }

    public function editDriver(Driver $driver)
    {
        $todas = Toda::all();
        return view('tricycle.driver.update-driver', compact('driver', 'todas'));
    }

    public function updateDriver(Request $request, Driver $driver)
    {
        $request->validate([
            'driverName' => 'required|min:2|max:100',
            'contactNumber' => [
                'required',
                'size:11',
                Rule::unique('tbl_driverinfo', 'driverPhoneNum')->ignore($driver->driverID, 'driverID')
            ],
            'plateNumber' => [
                'required',
                'max:6',
                Rule::unique('tbl_driverinfo', 'plateNumber')->ignore($driver->driverID, 'driverID')
            ],
            'tinPlate' => [
                'required',
                'regex:/^[0-9]{4}$/',
                Rule::unique('tbl_driverinfo', 'tinPlate')->ignore($driver->driverID, 'driverID')
            ],
            
            'todaID' => 'required'
        ]);

        $driver->update([
            'driverName' => $request->driverName,
            'driverPhoneNum' => $request->contactNumber,
            'plateNumber' => $request->plateNumber,
            'tinPlate' => $request->tinPlate,
            'todaID' => $request->todaID
        ]);

        return redirect()->route('drivers')->with('success', 'Updated Driver successfully.');
    }


    //QR Code para makita
    public function showQrCode($id)
    {
        // Fetch the driver information from the database
        $driver = Driver::find($id);

        // Check if driver exists
        if ($driver) {
            // Generate the QR code for the tinPlate
            $qrCode = SimpleQrCode::size(300)->generate($driver->tinPlate);

            return view('tricycle.driver.driver-qr', compact('qrCode', 'driver'));
        } else {
            return redirect()->back()->with('error', 'Driver not found');
        }
    }

    //QR Code para ma-download
    public function downloadQrCode($id)
    {
        $driver = Driver::find($id);

        if ($driver) {
            $qrCode = new QrCode($driver->tinPlate);
            $qrCode->setSize(300);

            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $result = $writer->write($qrCode);

            $qrImage = imagecreatefromstring($result->getString());

            // Text to display below the QR code
            $textLines = [
                "Driver Tin Plate: " . $driver->tinPlate,
                "Driver Name: " . $driver->driverName,
            ];

            $fontSize = 16;
            $fontFile = $_SERVER['DOCUMENT_ROOT'] . '/fonts/arial.ttf';
            $textColor = imagecolorallocate($qrImage, 0, 0, 0);

            // Calculate max width needed for the longest line
            $maxTextWidth = imagesx($qrImage);
            $wrappedText = [];
            foreach ($textLines as $line) {
                $words = explode(' ', $line);
                $currentLine = '';

                foreach ($words as $word) {
                    $testLine = $currentLine . ' ' . $word;
                    $testBox = imagettfbbox($fontSize, 0, $fontFile, $testLine);

                    // Check if the current line width is within the max width
                    if ($testBox[2] > $maxTextWidth) {
                        $wrappedText[] = trim($currentLine);
                        $currentLine = $word; // Start a new line with the current word
                    } else {
                        $currentLine = $testLine; // Append word to the current line
                    }
                }
                $wrappedText[] = trim($currentLine);
            }

            // Calculate total height for the wrapped text
            $totalTextHeight = 0;
            foreach ($wrappedText as $line) {
                $textBox = imagettfbbox($fontSize, 0, $fontFile, $line);
                $totalTextHeight += abs($textBox[5] - $textBox[1]) + 10;
            }

            // Create new image with combined height
            $combinedHeight = imagesy($qrImage) + $totalTextHeight + 20;
            $combinedImage = imagecreatetruecolor($maxTextWidth, $combinedHeight);

            $white = imagecolorallocate($combinedImage, 255, 255, 255);
            imagefill($combinedImage, 0, 0, $white);

            // Copy the QR code image to the combined image
            imagecopy($combinedImage, $qrImage, 0, 0, 0, 0, imagesx($qrImage), imagesy($qrImage));

            // Add each wrapped line of text below the QR code
            $y = imagesy($qrImage) + 10;
            foreach ($wrappedText as $line) {
                $textBox = imagettfbbox($fontSize, 0, $fontFile, $line);
                $textWidth = abs($textBox[4] - $textBox[0]);
                imagettftext(
                    $combinedImage,
                    $fontSize,
                    0,
                    (imagesx($qrImage) - $textWidth) / 2,
                    $y,
                    $textColor,
                    $fontFile,
                    $line
                );
                $y += abs($textBox[5] - $textBox[1]) + 10; // Move down for next line
            }

            // Output the image as PNG
            ob_start();
            imagepng($combinedImage);
            $imageData = ob_get_clean();

            imagedestroy($qrImage);
            imagedestroy($combinedImage);

            return response($imageData, 200)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="qr_code_' . $driver->tinPlate . '.png"');
        } else {
            return redirect()->back()->with('error', 'Driver not found');
        }
    }

    public function searchDriver(Request $request)
    {
        $query = $request->input('query'); // Search query for filtering drivers

        $drivers = Driver::when($query, function ($q) use ($query) {
                $q->where('driverName', 'LIKE', "%{$query}%")
                  ->orWhere('plateNumber', 'LIKE', "%{$query}%")
                  ->orWhere('tinPlate', 'LIKE', "%{$query}%");
            })
            ->get();

        return view('tricycle.driver.driver-all', compact('drivers', 'query'));
    }
}
