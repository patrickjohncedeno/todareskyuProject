import React, { useEffect, useState, useCallback } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView, RefreshControl, BackHandler } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import colors from './../../components/colors';
import AntDesign from '@expo/vector-icons/AntDesign';
import { useRouter } from 'expo-router';
import axios from 'axios';
import { API_BASE_URL } from './../../config/config';
import Ionicons from '@expo/vector-icons/Ionicons';
import { useFocusEffect } from '@react-navigation/native';

export default function IndexScreen() {
    const [user, setUser] = useState<any>(null);
    const [caseCounts, setCaseCounts] = useState({ inProcess: 0, underReview: 0, closed: 0 });
    const [refreshing, setRefreshing] = useState(false);
    const router = useRouter();
    const [notifications, setUnreadNotificationCount] = useState<number>(0);
    const [error, setError] = useState('');
    const [announcements, setAnnouncements] = useState([]);


   

    useEffect(() => {
        const fetchUserData = async () => {
            try {
                const userData = await AsyncStorage.getItem('user');
                if (userData) {
                    const parsedUser = JSON.parse(userData);
                    setUser(parsedUser);
                    console.log(parsedUser);
                    fetchCaseCounts(parsedUser.userID);
                }
            } catch (error) {
                console.error('Failed to fetch user data', error);
            }
        };

        fetchUserData();
        fetchNotifications();
        fetchAnnouncements();
    }, []);

    const fetchAnnouncements = async () => {
        try {
            const response = await axios.get(`${API_BASE_URL}/announcements`);
            if (response.data.success) {
                setAnnouncements(response.data.data);
            }
        } catch (error) {
            console.error('Failed to fetch announcements', error);
        }
    };

    const fetchNotifications = async () => {
        try {
            const userData = await AsyncStorage.getItem('user');
            if (userData) {
                const parsedUser = JSON.parse(userData);
                const response = await axios.get(`${API_BASE_URL}/notifications`, {
                    params: { userID: parsedUser.userID }
                });
                setUnreadNotificationCount(response.data.unread_count);
            }
        } catch (error) {
            setError('Failed to fetch notifications');
            console.error('Failed to fetch notifications', error);
        }
    };

    const fetchCaseCounts = async (userID: string) => {
        try {
            const response = await axios.get(`${API_BASE_URL}/getComplaintCounts`, {
                params: { userID }
            });
            setCaseCounts(response.data);
        } catch (error) {
            console.error('Failed to fetch case counts', error);
        } finally {
            setRefreshing(false);
        }
    };

    const onRefresh = useCallback(() => {
        setRefreshing(true);
        if (user) {
            fetchCaseCounts(user.userID);
            fetchNotifications();
            fetchAnnouncements().finally(() => setRefreshing(false));
        }
    }, [user]);

    useFocusEffect(
        useCallback(() => {
            const onBackPress = () => {
                // Prevent going back to this screen
                router.push('/(tabs)'); // Redirect to the main tabs or any other screen
                return true; // Override default back behavior
            };

            const backHandler = BackHandler.addEventListener('hardwareBackPress', onBackPress);

            return () => {
                backHandler.remove();
            };
        }, [])
    );

    if (!user) {
        return (
            <View style={styles.container}>
                <Text>Loading...</Text>
            </View>
        );
    }

    const handleReportRegisteredVehicle = () => {
        router.push('/complaint_form/insertTIN');
    };

    const handleReportUnregisteredVehicle = () => {
        router.push('/complaint_form/complaint_unreg');
    };

    const openNotif = () => {
        router.push('/notification/notification');
    };

   
    
    

    return (
        <ScrollView
            refreshControl={
                <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
            }
            contentContainerStyle={{ flexGrow: 1 }}
            style={{ backgroundColor: colors.merlot[100] }}
        >
            <View className='w-full self-center h-full'>
                <View className='bg-white rounded-b-3xl pt-2'>
                    <TouchableOpacity onPress={openNotif}>
                        <View className='items-end px-5 pt-5'>
                            <View className='relative'>
                                <Ionicons name="notifications" size={24} color="black" />
                                <Text className='absolute top-[-8px] left-[10px] bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center'>
                                    <Text className='w-full text-center justify-center items-center font-bold text-sm'>
                                        {notifications > 0 ? `${notifications}` : '0'}
                                    </Text>
                                </Text>
                            </View>
                        </View>
                    </TouchableOpacity>
                    <View className='p-5 flex-row gap-3 w-full'>
                        <Text className='text-5xl'>Hello, </Text>
                        <Text className='text-5xl font-semibold'>{user.firstName}!</Text>
                    </View>
                    <Text className='px-5 mt-3 mb-2 text-xl font-bold'>General Information</Text>
                    <View className='justify-between flex-row p-3'>
                        <View className='items-center w-1/3'>
                            <Text className='text-3xl pb-1'>{caseCounts.inProcess}</Text>
                            <Text>In Process</Text>
                        </View>
                        <View className='items-center w-1/3'>
                            <Text className='text-3xl pb-1'>{caseCounts.underReview}</Text>
                            <Text className='text-center'>Under Review</Text>
                        </View>
                        <View className='items-center w-1/3'>
                            <Text className='text-3xl pb-1'>{caseCounts.closed}</Text>
                            <Text>Closed Cases</Text>
                        </View>
                    </View>
                </View>
                <View className='flex-row gap-5 items-center justify-center'>
                    <TouchableOpacity onPress={handleReportRegisteredVehicle} style={{ width: 150, marginTop: 20 }}>
                        <View className='bg-white shadow-[20px_20px_5px_10px_#0000004d] p-6 rounded-xl justify-between aspect-square'>
                            <AntDesign name="addfile" size={35} color="black" />
                            <Text className='text-xl align-bottom font-semibold'>Report Registered Vehicle</Text>
                        </View>
                    </TouchableOpacity>
                    <TouchableOpacity onPress={handleReportUnregisteredVehicle} style={{ width: 150, marginTop: 20 }}>
                        <View className='bg-white shadow-[20px_20px_5px_10px_#0000004d] p-6 rounded-xl justify-between aspect-square'>
                            <AntDesign name="addfile" size={35} color="black" />
                            <Text className='text-xl align-bottom font-semibold'>Report Unregistered Vehicle</Text>
                        </View>
                    </TouchableOpacity>
                </View>

                <View className='p-5'>
    <Text className='text-xl font-bold mb-4'>Latest Announcements</Text>
    {announcements.length === 0 ? (
        <Text>No announcements available.</Text>
    ) : (
        announcements.map((announcement, index) => (
            <View
                key={index}
                className='mb-4 p-4 bg-white rounded-lg shadow-sm'
            >
                <Text className='text-lg font-semibold'>{announcement.title}</Text>
                <Text className='text-gray-600'>{announcement.content}</Text>
                <Text className='text-sm text-gray-400'>
                    Posted by {announcement.author} on {new Date(announcement.datePosted).toLocaleDateString()}
                </Text>
            </View>
        ))
    )}
</View>

            </View>
        </ScrollView>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
    },
});
