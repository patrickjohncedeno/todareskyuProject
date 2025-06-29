import React, { useEffect, useState } from 'react';
import { View, Text, TouchableOpacity, Image, Pressable, ActivityIndicator, BackHandler } from 'react-native';
import colors from './../../../components/colors';
import MaterialIcons from '@expo/vector-icons/MaterialIcons';
import { useNavigation } from '@react-navigation/native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { API_BASE_URL } from './../../../config/config';

export default function ProfileScreen() {
    const [profileData, setProfileData] = useState<any>(null);
    const [loading, setLoading] = useState(true);
    const navigation = useNavigation();

    // Fetch user data from AsyncStorage and API
    useEffect(() => {
        const fetchProfileData = async () => {
            try {
                const userData = await AsyncStorage.getItem('user');
                if (userData) {
                    const userId = JSON.parse(userData);
                    const response = await fetch(`${API_BASE_URL}/userinfo/${userId.firstName}`);
                    const data = await response.json();
                    console.log(userId.firstName)

                    if (response.ok) {
                        setProfileData(data.user);
                    } else {
                        console.error('Failed to fetch profile:', data.error || 'Unknown error');
                    }
                } else {
                    console.error('No user data found in AsyncStorage.');
                }
            } catch (error) {
                console.error('Error fetching profile data:', error);
            } finally {
                setLoading(false);
            }
        };

        fetchProfileData();

        // Handle back button press
        const backAction = () => {
            navigation.goBack(); // Navigate back to the previous screen
            return true;  // Prevent default back action
        };

        BackHandler.addEventListener('hardwareBackPress', backAction);

        // Cleanup the listener on component unmount
        return () => {
            BackHandler.removeEventListener('hardwareBackPress', backAction);
        };
    }, []); // Empty dependency array to run once on mount

    if (loading) {
        return (
            <View className="flex-1 justify-center items-center bg-white">
                <ActivityIndicator size="large" color={colors.merlot[800]} />
                <Text className="text-gray-600 mt-4">Loading profile...</Text>
            </View>
        );
    }

    if (!profileData) {
        return (
            <View className="flex-1 justify-center items-center bg-white">
                <Text className="text-red-500">Failed to load profile information.</Text>
            </View>
        );
    }

    return (
        <View className="flex-1" style={{ backgroundColor: colors.merlot[800] }}>
            <View className="relative flex-row justify-center items-center p-7 pt-8">
                <Pressable onPress={() => navigation.goBack()} className="absolute left-5 top-7 rounded-full p-1" style={{ backgroundColor: colors.merlot[900] }}>
                    <MaterialIcons name="keyboard-arrow-left" size={25} color="black" />
                </Pressable>
                <Text className="text-white text-2xl font-semibold">
                    Profile Information
                </Text>
            </View>

            {/* Profile Information Section */}
            <View className="bg-slate-100 p-6 rounded-t-3xl shadow-sm flex-1">
                <View className="bg-blue-950 rounded-full self-center mb-7 mt-2">
                    <Image
                        source={require('~/assets/pfp.png')}
                        className="h-36 aspect-square rounded-full"
                    />
                </View>

                <View className="gap-2">
                    {/* Name */}
                    <ProfileInfoRow label="Name" value={`${profileData.firstName} ${profileData.lastName}`} />
                    {/* Age */}
                    <ProfileInfoRow label="Age" value={profileData.age} />
                    {/* Address */}
                    <ProfileInfoRow label="Address" value={profileData.address} />
                    {/* Email */}
                    <ProfileInfoRow label="Email" value={profileData.email} />
                    {/* Phone Number */}
                    <ProfileInfoRow label="Phone Number" value={profileData.phoneNumber} />
                </View>
            </View>
        </View>
    );
}

const ProfileInfoRow = ({ label, value }: { label: string; value: any }) => (
    <View className="w-full mb-3 bg-slate-200 py-4 px-6 rounded-2xl flex-row items-center border border-slate-300">
        <View className="w-full">
            <Text className="text-gray-600 font-medium">{label}</Text>
            <View className="h-px w-full bg-slate-300 mt-1 mb-2" />
            <Text className="text-lg text-gray-900">{value}</Text>
        </View>
    </View>
);
