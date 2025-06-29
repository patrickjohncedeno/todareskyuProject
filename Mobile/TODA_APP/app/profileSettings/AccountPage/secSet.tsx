import React, { useEffect, useState } from 'react';
import { View, Text, FlatList, Pressable, BackHandler } from 'react-native';
import { useRouter } from 'expo-router';
import { useNavigation } from '@react-navigation/native';
import MaterialIcons from '@expo/vector-icons/MaterialIcons';
import colors from './../../../components/colors';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { API_BASE_URL } from './../../../config/config';
import axios from 'axios';

type Complaint = {
  id: string;
  headline: string;
  status: string;
  dateReported: string;
  violationName: string;
  meetingDate: any;
  reasonForDenying: any;
  resolutionDetail: any;
};

const ComplaintsList: React.FC = () => {
  const router = useRouter();
  const navigation = useNavigation();
  const [complaints, setComplaints] = useState<Complaint[]>([]);
  const [userID, setUserID] = useState<string | null>(null); // State to store userID

  useEffect(() => {
    // Fetch user data from AsyncStorage
    const fetchUserData = async () => {
      try {
        const userData = await AsyncStorage.getItem('user');
        if (userData) {
          const parsedUser = JSON.parse(userData);
          setUserID(parsedUser.userID); // Set userID to state
          console.log('User Data:', parsedUser);
          fetchComplaints(parsedUser.userID); // Pass userID to fetch complaints
        }
      } catch (error) {
        console.error('Error fetching user data:', error);
      }
    };

    // Fetch complaints when userID is set
    const fetchComplaints = async (userID: string) => {
      try {
        const response = await axios.get(`${API_BASE_URL}/getUserComplaints/${userID}`);
        
        if (response.status === 200) {
          const data = response.data;
          setComplaints(data.complaints);
        } else {
          console.error('Error fetching complaints:', response.status);
        }
      } catch (error) {
        console.error('Error fetching complaints:', error);
      }
    };

    fetchUserData();

    // Handle back button press
    const backAction = () => {
      navigation.goBack();  // Navigate back to the previous screen
      return true;  // Prevent default back action
    };

    BackHandler.addEventListener('hardwareBackPress', backAction);

    // Cleanup the listener on component unmount
    return () => {
      BackHandler.removeEventListener('hardwareBackPress', backAction);
    };
  }, []); // Empty dependency array to run once on mount

  const renderComplaint = ({ item }: { item: Complaint }) => (
    <View className="mb-4 w-full bg-slate-100 py-4 px-6 rounded-2xl border border-slate-300">
      <Text className="text-lg font-bold text-gray-800 mb-2">{item.violationName}</Text>
      <Text className="text-sm text-gray-600">Status: {item.status}</Text>

      {item.status === 'Pending' && (
        <Text className="text-sm text-gray-600">Waiting for CTMO's response.</Text>
      )}

      {item.status === 'In Process' && (
        <Text className="text-sm text-gray-600">Meeting Date: {item.meetingDate}</Text>
      )}

      {item.status === 'Denied' && (
        <Text className="text-sm text-gray-600">Reason For Denying: {item.reasonForDenying}</Text>
      )}

      {(item.status === 'Settled' || item.status === 'Unresolved') && (
        <Text className="text-sm text-gray-600">Resolution Details: {item.resolutionDetail}</Text>
      )}

      <Text className="text-sm text-gray-600">Reported on: {item.dateReported}</Text>
    </View>
  );

  return (
    <View className="flex-1 bg-white p-4 pt-8">
      <Pressable
        onPress={() => router.push('/(tabs)/profile')}
        className="absolute z-10 left-5 top-7 rounded-full p-1"
        style={{ backgroundColor: colors.merlot[100] }}
      >
        <MaterialIcons name="keyboard-arrow-left" size={25} color="black" />
      </Pressable>
      <Text className="text-2xl font-bold text-gray-800 text-center mb-8">My Complaints</Text>
      <FlatList
        data={complaints}
        keyExtractor={(item) => item.id}
        renderItem={renderComplaint}
        ListEmptyComponent={<Text className="text-center text-gray-500">No complaints reported yet.</Text>}
      />
    </View>
  );
};

export default ComplaintsList;
