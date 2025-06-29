import React, { useState, useEffect, useCallback  } from 'react';
import { Text, View, ScrollView, Button, TouchableOpacity, TextInput, Alert, ActivityIndicator, BackHandler, Modal } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { router, useLocalSearchParams } from 'expo-router';
import axios from 'axios'; // Import axios
import { API_BASE_URL } from './../../config/config';
import * as ImagePicker from 'expo-image-picker';
import { Image } from 'react-native';
import * as FileSystem from 'expo-file-system';
import colors from './../../components/colors';
import { Dimensions } from 'react-native';
import { useFocusEffect } from '@react-navigation/native';

export default function Complaint() {
  const [text, setText] = useState('');
  const [plate, setPlate] = useState('');
  const [desc, setDesc] = useState('');
  const [color, setColor] = useState('');
  const [userData, setUserData] = useState<any>(null);
  const [selectedViolation, setSelectedViolation] = useState<string | null>(null);
  const [loading, setLoading] = useState(false); // Add loading state
  const [isModalVisible, setModalVisible] = useState(false);
 
  const [isDropdownVisible, setDropdownVisible] = useState(false);
  const { height } = Dimensions.get('window');
  const today = new Date();
  const formattedDate = today.toISOString().split('T')[0]; // Format as YYYY-MM-DD

  const violationsList = [
    { label: 'Illegal Parking', key: 'Illegal Parking' },
    { label: 'Obstruction', key: 'Obstruction' },
    { label: 'Disregarding Traffic Lights, Signs, Officer', key: 'Disregarding Traffic Lights, Signs, Officer' },
    { label: "Driving Without or With Delinquent Driver's License", key: "Driving Without or With Delinquent Driver's License" },
    { label: 'Refuse to Convey Passenger', key: 'Refuse to convey passenger' },
    { label: 'Overloading', key: 'Overloading' },
    { label: 'Over Speeding/Reckless Driving', key: 'Over speeding/Reckless Driving' },
    { label: 'Counter Flow', key: 'Counter flow' },
    { label: 'Overpricing', key: 'Overpricing' },
    { label: 'Section 24 (Proper Attire)', key: 'Proper Attire' },
    { label: 'Loading in Prohibited Zone', key: 'Loading in prohibited zone' },
    { label: 'No OR/CR', key: 'No OR/CR' },
    { label: 'Arrogant Driver', key: 'Arrogant Driver' },
  ];

  const fetchUserData = async () => {
    try {
      const userData = await AsyncStorage.getItem('user');
      if (userData) {
        setUserData(JSON.parse(userData));
      }
    } catch (error) {
      console.error('Failed to fetch user data', error);
    }
  };
  useFocusEffect(
    useCallback(() => {
      // Add BackHandler listener when the page is focused
      const backHandler = BackHandler.addEventListener('hardwareBackPress', handleBackPress);

      return () => {
        // Remove BackHandler listener when the page loses focus
        backHandler.remove();
      };
    }, [])
  );

  useEffect(() => {
    fetchUserData();
      // Add BackHandler listener
    
  }, []);

  const handleBackPress = () => {
    setModalVisible(true); // Show modal when back is pressed
    return true; // Prevent default back action
  };

  const handleModalYes = () => {
    setModalVisible(false);
    router.push('/(tabs)'); // Navigate to the index page
  };

  const handleModalNo = () => {
    setModalVisible(false); // Close the modal
  };

  const toggleViolation = (key: string) => {
    setSelectedViolation(key === selectedViolation ? null : key);
    setDropdownVisible(!isDropdownVisible)
  };

  const toggleDropdown = () => {
    setDropdownVisible(!isDropdownVisible);
  };




  const fetchViolationID = async (selectedViolation: string): Promise<string | null> => {
    try {
        const response = await fetch(`${API_BASE_URL}/get-violation-id`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ selectedViolation }),
        });

        const data = await response.json();

        if (response.ok) {
            // Return the fetched violationID
            return data.violationID;
        } else {
            console.error(data.error);
            return null;
        }
    } catch (error) {
        console.error('Error fetching violationID:', error);
        return null;
    }
};


const handleSubmit = async () => {
  try {
    if (!plate.trim()) {
      Alert.alert('Missing Information', 'Please provide plate number of the tricycle.');
      return;
    }
    if (!desc.trim()) {
      Alert.alert('Missing Information', 'Please provide description about the tricycle.');
      return;
    }
    if (!color.trim()) {
      Alert.alert('Missing Information', 'Please provide the color of the tricycle.');
      return;
    }
    if (!image) {
      Alert.alert('Missing Information', 'Please provide evidence photo.');
      return;
    }
    if (!selectedViolation) {
      Alert.alert('Missing Information', 'Please select a violation before submitting.');
      return;
    }
    

    if (!text.trim()) {
      Alert.alert('Missing Information', 'Please provide details about the incident.');
      return;
    }
    
    
    
      // Ensure selectedViolation is set before proceeding
      if (selectedViolation) {
          // Fetch the violation ID and wait for the result
          setLoading(true); // Start loading
          const fetchedViolationID = await fetchViolationID(selectedViolation);

          if (fetchedViolationID) {
              // Submit the complaint with the fetched violation ID
              await submitComplaint(fetchedViolationID);
          } else {
              console.error('Failed to fetch violation ID');
          }
      } else {
          console.error('No violation selected');
      }
  } catch (error) {
      console.error('Error during submission:', error);
  }
};

  
    const uriToBase64 = async (uri: string): Promise<string> => {
      try {
        const base64String = await FileSystem.readAsStringAsync(uri, {
          encoding: FileSystem.EncodingType.Base64,
        });
        return `data:image/jpeg;base64,${base64String}`; // Adjust the MIME type if needed
      } catch (error) {
        console.error('Error converting URI to base64:', error);
        return '';
      }
    };
    
    const submitComplaint = async (violationID: string) => {
      try {
        const formData = new FormData();
        formData.append('userID', userData?.userID);
        formData.append('location', 'San Pablo City');
        formData.append('description', text);
        formData.append('violationID', violationID);
        formData.append('plateNumber', plate);
        formData.append('tricycleDescription', desc);
        formData.append('tricycleColor', color);
    
        if (image) {
          formData.append('evidencePhoto', {
            uri: image,
            type: 'image/jpeg', // or the appropriate type if it's png
            name: 'evidence.jpg',
          } as any); // Cast to 'any' for compatibility
        }
    
        const response = await axios.post(`${API_BASE_URL}/submit-complaint-unreg`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });
    
        if (response.status === 201) {
          Alert.alert('Success', 'Complaint Reported!', [{ text: 'OK', onPress: () => router.push('/(tabs)') }]);
        } else {
          console.error('Failed to submit complaint');
        }
    
        console.log(response.data.message);
      } catch (error) {
        if (axios.isAxiosError(error)) {
          console.error('Error response data:', error.response?.data);
        } else if (error instanceof Error) {
          console.error('Error message:', error.message);
        } else {
          console.error('Unexpected error:', error);
        }
      }
    };
    
    


const [image, setImage] = useState<string | null>(null);

const pickImage = async () => {
    // Request permission to access media library
    const permissionResult = await ImagePicker.requestMediaLibraryPermissionsAsync();

    if (permissionResult.granted === false) {
        alert("Permission to access camera roll is required!");
        return;
    }

    // Launch image picker
    const result = await ImagePicker.launchImageLibraryAsync({
        mediaTypes: ImagePicker.MediaTypeOptions.All,
        aspect: [4, 3],
        quality: 1,
    });

    if (!result.canceled) {
        setImage(result.assets[0].uri); // Set the image URI
    }
};


  

  return (
    <ScrollView keyboardShouldPersistTaps="handled" style={{ flex: 1 }}    contentContainerStyle={{ flexGrow: 1 }}
    nestedScrollEnabled={true}>
    <View className='p-3 flex-1' style={{ backgroundColor: colors.merlot[300] }}>
    <View className=' p-3 rounded-md' style={{ backgroundColor: colors.merlot[100]}}>
      <Text className='font-bold text-3xl pb-2 pt-2'>Complaint Form (Colorum)</Text>
        {/* Complaint Information */}
        <Text className='pt-4'>Complainant Information</Text>
        <View
          style={{
            borderBottomColor: 'black',
            borderBottomWidth: 1,
            marginVertical: 6,
          }}
        />
        <View>
          <View style={{ flexDirection: 'row', marginBottom: 10 }}>
            <Text style={{ width: 130 }} className='font-bold'>Name of Complaint:</Text>
            <Text>{userData?.firstName} {userData?.lastName}</Text>
          </View>

          <View style={{ flexDirection: 'row', marginBottom: 10 }}>
            <Text style={{ width: 130 }} className='font-bold'>Address of Complaint:</Text>
            <Text>{userData?.address}</Text>
          </View>

          <View style={{ flexDirection: 'row' }}>
            <Text style={{ width: 130 }} className='font-bold'>Contact No./s:</Text>
            <Text>{userData?.phoneNumber}</Text>
          </View>
        </View>

        {/* Vehicle Information */}
        <Text className='pt-5'>Tricycle Information</Text>
        <View
          style={{
            borderBottomColor: 'black',
            borderBottomWidth: 1,
            marginVertical: 6,
          }}
        />
        <View style={{ flexDirection: 'row', marginBottom: 5 }}>
            <Text style={{ width: 130 }} className='font-bold'>Plate Number:</Text>
            <TextInput
              style={{height: 30}}
              placeholder="Type the plate number."
              onChangeText={newPlate => setPlate(newPlate)}
              defaultValue={plate}
              className='bg-slate-100 p-2 w-7/12'
            />
        </View>
        <View style={{ flexDirection: 'row', marginBottom: 5 }}>
            <Text style={{ width: 130 }} className='font-bold'>Tricycle Description:</Text>
            <TextInput
              style={{height: 30}}
              placeholder="Description of the tricycle."
              onChangeText={newDesc => setDesc(newDesc)}
              defaultValue={desc}
              className='bg-slate-100 p-2 w-7/12'
            />
        </View>
        <View style={{ flexDirection: 'row', marginBottom: 6 }}>
            <Text style={{ width: 130 }} className='font-bold'>Tricycle Color:</Text>
            <TextInput
              style={{height: 30}}
              placeholder="Color of the tricycle."
              onChangeText={newColor => setColor(newColor)}
              defaultValue={color}
              className='bg-slate-100 p-2 w-7/12'
            />
        </View>
        <View style={{ flexDirection: 'row', marginBottom: 6 }}>
            <Text style={{ width: 130 }} className='font-bold'>Evidence Photo:</Text>
            <View>
              <TouchableOpacity onPress={pickImage}>
                  <Text className=' p-2 text-white'
                  style={{ backgroundColor: colors.merlot[600]}}
                  >
                    Pick an image from gallery
                  </Text>
              </TouchableOpacity>
              
              <View>
                {image && (
                    <Image
                        source={{ uri: image }}
                        style={{ width: 180, height: 120, marginTop: 10 }}
                    />
                )}
              </View>
            </View>
            
        </View>


        

        {/* Incident Information */}
        <Text className='pt-2'>Incident Information</Text>
        <View
          style={{
            borderBottomColor: 'black',
            borderBottomWidth: 1,
            marginVertical: 6,
          }}
        />
        <View>
          <View style={{ flexDirection: 'row', marginBottom: 10 }}>
            <Text style={{ width: 130 }} className='font-bold'>Date of Incident:</Text>
            <Text>{formattedDate}</Text>
          </View>

          <View style={{ flexDirection: 'row', marginBottom: 10 }}>
            <Text style={{ width: 130 }} className='font-bold'>Place of Incident:</Text>
            <Text>San Pablo City</Text>
          </View>
        </View>

        {/* Violation Selection */}
        <Text className='pt-3'>Select Violations</Text>
        <View
          style={{
            borderBottomColor: 'black',
            borderBottomWidth: 1,
            marginVertical: 6,
          }}
        />

        {/* Display selected violation */}
        <Text style={{ marginTop: 5, fontWeight: 'bold', marginBottom: 10 }}>
          Selected Violation: {violationsList.find(v => v.key === selectedViolation)?.label || 'None'}
        </Text>

        {/* Button to toggle the visibility of the dropdown */}
         {/* Button to toggle the visibility of the dropdown */}
         <TouchableOpacity
          onPress={toggleDropdown}
          style={{
            paddingVertical: 12,
            paddingHorizontal: 20,
            borderRadius: 8,
            alignItems: 'center',
            backgroundColor: colors.merlot[600], // Inline background color
          }}
        >
          <Text
            style={{
              color: '#fff', // Inline text color
              fontSize: 16,
              fontWeight: 'bold',
            }}
          >
            {isDropdownVisible ? 'Hide Violations' : 'Show Violations'}
          </Text>
        </TouchableOpacity>

        {/* Display dropdown only if visible */}
        {isDropdownVisible && (
          <ScrollView
            style={{ maxHeight: 173 }}
            contentContainerStyle={{ flexGrow: 1 }}
            nestedScrollEnabled={true}
            className='pt-2'
          >
            <View style={{ flexDirection: 'row', flexWrap: 'wrap', justifyContent: 'space-between' }}>
              {violationsList.map((violation, index) => (
                <TouchableOpacity key={index} onPress={() => toggleViolation(violation.key)}>
                  <Text
                    style={{
                      fontSize: 12,
                      padding: 9,
                      backgroundColor: selectedViolation === violation.key ? 'lightgray' : 'white',
                      borderColor: 'black',
                      borderWidth: 1,
                      borderRadius: 5,
                      marginBottom: 5,
                      width: 150,
                      height: 45,
                    }}
                  >
                    {violation.label}
                  </Text>
                </TouchableOpacity>
              ))}
            </View>
          </ScrollView>
        )}

        

        {/* Violation Selection */}
        <Text className='pt-5'>Summary of Incident</Text>
        <View
          style={{
            borderBottomColor: 'black',
            borderBottomWidth: 1,
            marginVertical: 6,
          }}
        />
        <TextInput
        style={{height: 40}}
        placeholder="Type details about the incident!"
        onChangeText={newText => setText(newText)}
        defaultValue={text}
        className='bg-slate-100 p-4'
      />
     <TouchableOpacity 
        className='self-end p-6 w-full mt-2 rounded-lg'
        style={{ backgroundColor: colors.merlot[800]}}
        onPress={handleSubmit} // Add the onPress event
      ><Text className='self-center text-slate-50 font-bold text-lg'>SUBMIT COMPLAINT</Text>
      </TouchableOpacity>

      </View>
      
    </View>
    
     {/* Loader */}
     {loading && (
        <View className="absolute inset-0 bg-slate-100 opacity-85 justify-center items-center">
          <ActivityIndicator size="large" color="#000000" />
          <Text className="text-black font-bold text-lg mt-4">Please wait...</Text>
        </View>
      )}
      {/* Modal */}
      <Modal
        transparent={true}
        visible={isModalVisible}
        animationType="slide"
        onRequestClose={() => setModalVisible(false)}
      >
        <View
          style={{
            flex: 1,
            justifyContent: 'center',
            alignItems: 'center',
            backgroundColor: 'rgba(0, 0, 0, 0.5)',
          }}
        >
          <View
            style={{
              width: '80%',
              padding: 20,
              backgroundColor: 'white',
              borderRadius: 10,
              alignItems: 'center',
            }}
          >
            <Text style={{ fontSize: 18, fontWeight: 'bold', marginBottom: 15 }}>
              Are you sure you want to go back? Your progress will not be saved.
            </Text>

            <View style={{ flexDirection: 'row', justifyContent: 'space-between', width: '100%' }}>
              <TouchableOpacity
                onPress={handleModalYes}
                style={{
                  backgroundColor: 'red',
                  padding: 10,
                  borderRadius: 5,
                  flex: 1,
                  alignItems: 'center',
                  marginRight: 10,
                }}
              >
                <Text style={{ color: 'white', fontWeight: 'bold' }}>Yes</Text>
              </TouchableOpacity>

              <TouchableOpacity
                onPress={handleModalNo}
                style={{
                  backgroundColor: 'green',
                  padding: 10,
                  borderRadius: 5,
                  flex: 1,
                  alignItems: 'center',
                  marginLeft: 10,
                }}
              >
                <Text style={{ color: 'white', fontWeight: 'bold' }}>No</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
      </ScrollView>
  );
}
