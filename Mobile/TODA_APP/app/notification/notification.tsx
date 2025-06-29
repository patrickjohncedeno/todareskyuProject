import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, FlatList, ActivityIndicator, TouchableOpacity, BackHandler } from 'react-native';
import axios from 'axios';
import { API_BASE_URL } from './../../config/config';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';
import colors from './../../components/colors';

const NotificationScreen = () => {
    const [notifications, setNotifications] = useState<any[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const router = useRouter();

    useEffect(() => {
        const handleBackPress = () => {
            router.push('./../(tabs)');  // Navigate back to NotificationScreen
            return true;  // Prevent default behavior (i.e., exit app)
        };

        // Add event listener for back button press
        BackHandler.addEventListener('hardwareBackPress', handleBackPress);

        // Clean up the event listener when the component is unmounted
        return () => {
            BackHandler.removeEventListener('hardwareBackPress', handleBackPress);
        };
    }, [router]);

    useEffect(() => {
        const fetchNotifications = async () => {
            
            try {
                const userData = await AsyncStorage.getItem('user');
                if (userData) {
                    const parsedUser = JSON.parse(userData);
                    const response = await axios.get(`${API_BASE_URL}/getNotification`, {
                        params: { userID: parsedUser.userID }
                    });
                    console.log('Response Data:', response.data);
    
                    // Reverse the notifications so the latest will be at the top
                    setNotifications(response.data.reverse());
                }
            } catch (error) {
                setError('Failed to fetch notifications');
                console.error('Failed to fetch notifications', error);
            } finally {
                setLoading(false);
            }
        };
    
        fetchNotifications();
    }, []);

   
    

    // Function to render each notification item
    const renderNotification = ({ item }: { item: any }) => {
        // Parse the complaint details to access the resolutionDetail
       
    
        const clickNotif = async () => {
            try {
                // Call the API to mark the notification as read
                const response = await axios.put(`${API_BASE_URL}/notifications/${item.id}/mark-as-read`);
    
                // Safely access resolutionDetail from the parsed complaint object
            
    
                if (response.data.message) {
                    console.log(response.data.message);
                }
    
                // Pass complaint details to the notificationInfo screen
                router.push({
                    pathname: '/notification/notificationInfo',
                    params: {
                        complaintDetails: JSON.stringify(item.complaint_details),
                        notificationType: item.notification_type,
                        violationName: item.violationName,
                        meetingDate: item.meeting_date,
                        denialReason: item.denial_reason,
                        tinPlate: item.tinPlate,
                        resolverDate: item.dateResolve
                    }
                });
            } catch (error) {
                console.error('Failed to mark notification as read', error);
            }
        };
    
        return (
            <TouchableOpacity style={[{padding: 16,
                marginBottom: 10,
                borderRadius: 8,
                backgroundColor: item.readNotif === 1 ? colors.merlot[600] : 'white', 
                shadowColor: '#000',
                shadowOffset: { width: 0, height: 2 },
                shadowOpacity: 0.3,
                shadowRadius: 4,
                elevation: 2,}]} onPress={clickNotif}>
                <Text style={styles.notificationType}>Complaint Against Tricycle with Tin Plate:</Text>
                {/* Replace className with style */}
                <Text style={{ textAlign: 'center', fontSize: 24, fontWeight: 'bold', paddingTop: 10 }}>
                    {item.tin_plate}
                </Text>
                <Text style={styles.notificationType}>Message: {item.notification_type}</Text>
            </TouchableOpacity>
        );
    };
    
    
    // Display loading indicator while fetching data
    if (loading) {
        return (
            <View style={styles.container}>
                <ActivityIndicator size="large" color="#0000ff" />
            </View>
        );
    }

    // Display error message if there's an error
    if (error) {
        return (
            <View style={styles.container}>
                <Text style={styles.errorMessage}>{error}</Text>
            </View>
        );
    }

    // Main view displaying notifications 
    return (
        <View style={styles.container}>
            <Text style={styles.header}>Notifications</Text>
            {notifications.length === 0 ? ( // Check if notifications array is empty
                <Text style={styles.noNotifications}>---- There is no notification ----</Text>
            ) : (
                <FlatList
                    data={notifications}
                    renderItem={renderNotification}
                    keyExtractor={(item) => item.id.toString()} // Ensure each notification has a unique ID
                />
            )}
        </View>
    );
    
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        padding: 16,
        backgroundColor: '#f9f9f9',
    },
    header: {
        fontSize: 24,
        fontWeight: 'bold',
        marginBottom: 16,
    },
    notificationCard: {
        padding: 16,
        marginBottom: 10,
        borderRadius: 8,
        backgroundColor: '#ffffff',
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.3,
        shadowRadius: 4,
        elevation: 2,
    },
    notificationType: {
        fontSize: 18,
        fontWeight: 'bold',
    },
    violationName: {
        fontSize: 16,
        marginTop: 5,
        color: '#555',
    },
    meetingDate: {
        fontSize: 16,
        marginTop: 5,
    },
    denialReason: {
        fontSize: 16,
        marginTop: 5,
        color: 'red',
    },
    errorMessage: {
        fontSize: 16,
        color: 'red',
        textAlign: 'center',
    },
    noNotifications: {
        fontSize: 16,
        fontWeight: 'bold',
        textAlign: 'center',
        marginTop: 20,
        color: '#777',
    },
    
});

export default NotificationScreen;
