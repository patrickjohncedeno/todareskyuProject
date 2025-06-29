import React, { useState } from 'react';
import { Text, View, StyleSheet, Button } from 'react-native';
import { CameraView, useCameraPermissions } from 'expo-camera';
import { useRouter } from 'expo-router';
import colors from '~/components/colors';
import { API_BASE_URL } from './../../config/config';

export default function App() {
  const [permission, requestPermission] = useCameraPermissions();
  const [scannedData, setScannedData] = useState<string | null>(null);
  const [scanned, setScanned] = useState<boolean>(false);
  const [isNavigating, setIsNavigating] = useState<boolean>(false); // Temporary flag for navigation
  const router = useRouter();

  if (!permission) {
    return <View />;
  }

  if (!permission.granted) {
    return (
      <View style={styles.container}>
        <Text style={styles.message}>We need your permission to show the camera</Text>
        <Button onPress={requestPermission} title="Grant Permission" />
      </View>
    );
  }

  const handleBarcodeScanned = async ({ type, data }: { type: string; data: string }) => {
    if (scanned || isNavigating) return; // Prevent multiple scans or navigation

    setScanned(true);
    setScannedData(data);

    try {
      const response = await fetch(`${API_BASE_URL}/driver/${data}`);
      const driverData = await response.json();

      setIsNavigating(true); // Set navigating flag before navigating
      router.push({
        pathname: '/complaint_form/complaint_scan',
        params: { driverData: JSON.stringify(driverData) },
      });
    } catch (error) {
      console.error('Error fetching driver data:', error);
      setScanned(false);
    } finally {
      // Reset scanned and navigating state only after a short delay to prevent multiple reloads
      setTimeout(() => {
        setScanned(false);
        setIsNavigating(false);
      }, 1000);
    }
  };

  return (
    <View style={{ flex: 1, justifyContent: 'center', backgroundColor: colors.merlot[100] }}>
      <View
        style={{ backgroundColor: 'white', width: '80%', alignSelf: 'center', padding: 10, borderRadius: 10, height: 500 }}
      >
        <View style={{ width: '100%', alignItems: 'center' }} className='bg-slate-100 rounded-md p-4'>
          <Text style={{ fontSize: 18, color: 'black', padding: 10 }}>
            Align QR code to frame
          </Text>
        </View>
        <View style={{ alignSelf: 'center', height: 240, aspectRatio: 1, borderRadius: 10, overflow: 'hidden', marginTop: 25 }}>
          <CameraView
            style={{ flex: 1 }}
            facing='back'
            onBarcodeScanned={handleBarcodeScanned}
            barcodeScannerSettings={{
              barcodeTypes: ['qr', 'pdf417'],
            }}
          />
        </View>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    alignSelf: 'center',
    backgroundColor: 'white',
    width: 300,
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  message: {
    textAlign: 'center',
    paddingBottom: 10,
  },
});
