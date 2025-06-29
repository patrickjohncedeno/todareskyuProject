import React, { useState, useEffect, useCallback } from 'react';
import { Text, View, ScrollView, Button, TouchableOpacity, TextInput, Alert, ActivityIndicator, BackHandler, Modal } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { router, useFocusEffect, useLocalSearchParams } from 'expo-router';
import * as Location from 'expo-location'; // Import expo-location
import axios from 'axios'; // Import axios
import { API_BASE_URL } from './../../config/config';
import colors from './../../components/colors';
import { Dimensions } from 'react-native';

export default function Complaint() {
  const [text, setText] = useState('');
  const [userData, setUserData] = useState<any>(null);
  const [selectedViolation, setSelectedViolation] = useState<string | null>(null);
  const [violationID, setViolationID] = useState<string | null>(null);
  const [isDropdownVisible, setDropdownVisible] = useState(false);
  const [location, setLocation] = useState<string | null>(null);
  const [loading, setLoading] = useState(false); // Add loading state
  const [isModalVisible, setModalVisible] = useState(false);

  const today = new Date();
  const formattedDate = today.toISOString().split('T')[0]; // Format as YYYY-MM-DD

  
  const { height } = Dimensions.get('window');
  const { driverData } = useLocalSearchParams();

  let driver = null;
  try {
    driver = driverData ? JSON.parse(driverData as string) : null;
    console.log(driver);
  } catch (error) {
    console.error("Failed to parse driverData:", error);
  }

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
    setDropdownVisible(!isDropdownVisible);
  };

  const toggleDropdown = () => {
    setDropdownVisible(!isDropdownVisible);
  };

  // const getLocation = async () => {
  //   try {
  //     const { status } = await Location.requestForegroundPermissionsAsync();
  //     if (status !== 'granted') {
  //       Alert.alert('Permission denied', 'Location permission is required to submit a complaint.');
  //       return;
  //     }

  //     const location = await Location.getCurrentPositionAsync({});
  //     const reverseGeocode = await Location.reverseGeocodeAsync(location.coords);
      
  //     // Assuming the location is in the Philippines
  //     const { city, region } = reverseGeocode[0];
  //     setLocation(`${region}, ${city}`);
  //   } catch (error) {
  //     console.error('Error getting location:', error);
  //     Alert.alert('Error', 'Failed to retrieve location.');
  //   }
  // };
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

const submitComplaint = async (violationID: string) => {
  try {
      const response = await axios.post(`${API_BASE_URL}/submit-complaint`, {
          userID: userData?.userID,
          driverID: driver?.driverID,
          location: 'San Pablo City',
          description: text,
          violationID: violationID,
      });
      if (response.status === 201) {
        Alert.alert(
          'Success',
          'Complaint Reported!',
          [
            {
              text: 'OK',
              onPress: () => {
                // Navigate to index.tsx after the alert is confirmed
                router.push('/(tabs)');
              },
            },
          ]
        );
      } else {
        console.error('Failed to submit complaint');
      }
      
      console.log(response.data.message); // Handle successful submission
      // Navigate to another screen or show a success message
  } catch (error) {
      // Type assertion to check if the error is an AxiosError
      if (axios.isAxiosError(error)) {
          console.error('Error response data:', error.response?.data);
          console.error('Error response status:', error.response?.status);
          console.error('Error response headers:', error.response?.headers);
      } else if (error instanceof Error) {
          console.error('Error message:', error.message);
      } else {
          console.error('Unexpected error:', error);
      }
  }
};

    

const handleSubmit = async () => {
  try {
    // Check if necessary fields are filled
    if (!selectedViolation) {
      Alert.alert('Missing Information', 'Please select a violation before submitting.');
      return;
    }

    if (!text.trim()) {
      Alert.alert('Missing Information', 'Please provide details about the incident.');
      return;
    }

    setLoading(true); // Start loading

    // Fetch the violation ID and wait for the result
    const fetchedViolationID = await fetchViolationID(selectedViolation);

    if (fetchedViolationID) {
      // Submit the complaint with the fetched violation ID
      await submitComplaint(fetchedViolationID);
    } else {
      Alert.alert('Error', 'Failed to fetch violation ID. Please try again.');
    }
  } catch (error) {
    console.error('Error during submission:', error);
    Alert.alert('Error', 'An unexpected error occurred. Please try again.');
  } finally {
    setLoading(false); // Stop loading
  }
};




  

  return (
    <ScrollView
       contentContainerStyle={{ flexGrow: 1 }}
      nestedScrollEnabled={true}>
    
    <View className='p-3 flex-1' style={{ backgroundColor: colors.merlot[300]}}>
      <View className=' p-3 rounded-md' style={{ backgroundColor: colors.merlot[100]}}>
      <Text className='font-bold text-3xl pb-2 pt-2'>Complaint Form (Registered)</Text>
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

          <View style={{ flexDirection: 'row', marginBottom: 10 }}>
            <Text style={{ width: 130 }} className='font-bold'>Contact No./s:</Text>
            <Text>{userData?.phoneNumber}</Text>
          </View>
        </View>

        {/* Driver Information */}
        <Text className='pt-2'>Driver Information</Text>
        <View
          style={{
            borderBottomColor: 'black',
            borderBottomWidth: 1,
            marginVertical: 6,
          }}
        />
        <View>
          <View style={{ flexDirection: 'row', marginBottom: 10 }}>
            <Text style={{ width: 130 }} className='font-bold'>Name of Driver:</Text>
            <Text>{driver?.driverName}</Text>
          </View>

          <View style={{ flexDirection: 'row', marginBottom: 10 }}>
            <Text style={{ width: 130 }} className='font-bold'>MTOP No:</Text>
            <Text>{driver?.tinPlate}</Text>
          </View>

          <View style={{ flexDirection: 'row', marginBottom: 10 }}>
            <Text style={{ width: 130 }} className='font-bold'>TODA:</Text>
            <Text>{driver?.todaName}</Text>
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
        <Text style={{ marginTop: 5, fontWeight: 'bold', marginBottom: 10 }} >
          Selected Violation: {violationsList.find(v => v.key === selectedViolation)?.label || 'None'}
        </Text>

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
                <TouchableOpacity key={index} onPress={() => toggleViolation(violation.key)} >
                  <Text
                    style={{
                      fontSize: 13,
                      padding: 10,
                      backgroundColor: selectedViolation === violation.key ? 'lightgray' : 'white',
                      borderColor: 'black',
                      borderWidth: 1,
                      borderRadius: 5,
                      marginBottom: 5,
                      width: 150,
                      height: 50,
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
        className='self-end p-6 w-full mt-10 rounded-lg bg-slate-500'
        onPress={handleSubmit} // Add the onPress event
        style={{backgroundColor: colors.merlot[800]}}
      >
        <Text className='self-center text-slate-50 font-bold text-lg'>SUBMIT COMPLAINT</Text>
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
