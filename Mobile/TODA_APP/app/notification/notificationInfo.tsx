import React, { useEffect } from 'react';
import { View, Text, ScrollView, BackHandler } from 'react-native';
import { useLocalSearchParams, useRouter } from 'expo-router';

const NotificationInfo = () => {
    const { complaintDetails, notificationType, denialReason, meetingDate, tinPlate } = useLocalSearchParams();
    const router = useRouter();

    // Safely parse complaintDetails if it's a string
    const complaint = typeof complaintDetails === 'string' ? JSON.parse(complaintDetails) : {};

    // Access the violation name from the nested structure
    const violationName = complaint.violations?.violationName || 'N/A';
    const resolveDate = complaint.dateResolve;
    const resoDetail = complaint.resolutionDetail;

    // Handle back button press
    useEffect(() => {
        const handleBackPress = () => {
            router.push('/notification/notification');  // Navigate back to NotificationScreen
            return true;  // Prevent default behavior (i.e., exit app)
        };

        // Add event listener for back button press
        BackHandler.addEventListener('hardwareBackPress', handleBackPress);

        // Clean up the event listener when the component is unmounted
        return () => {
            BackHandler.removeEventListener('hardwareBackPress', handleBackPress);
        };
    }, [router]);

    return (
        <ScrollView style={{ flex: 1, backgroundColor: '#f9f9f9' }}>
            <View style={{ padding: 16 }}>
                <View style={{ backgroundColor: '#fff', borderRadius: 8, shadowColor: '#000', shadowOffset: { width: 0, height: 2 }, shadowOpacity: 0.3, shadowRadius: 4, padding: 16, marginBottom: 16 }}>
                    <Text style={{ fontSize: 20, fontWeight: 'bold', color: '#333', marginBottom: 8 }}>Notification Summary</Text>
                    <Text style={{ color: '#555' }}>
                        <Text style={{ fontWeight: 'bold' }}>Type:</Text> {notificationType || 'N/A'}
                    </Text>
                    {notificationType === 'Denied' && (
                        <Text style={{ color: '#555' }}>
                            <Text style={{ fontWeight: 'bold' }}>Reason:</Text> {denialReason || 'N/A'}
                        </Text>
                    )}
                    {notificationType === 'Meeting Set' && (
                        <Text style={{ color: '#555' }}>
                            <Text style={{ fontWeight: 'bold' }}>Meeting Date:</Text> {meetingDate || 'N/A'}
                        </Text>
                    )}
                    {notificationType === 'Resolved' && (
                        <Text style={{ color: '#555' }}>
                             <Text style={{ fontWeight: 'bold' }}>Resolved Date:</Text> {resolveDate || 'N/A'}
                        </Text>
                    )}
                    {notificationType === 'Resolved' && (
                        <Text style={{ color: '#555' }}>
                             <Text style={{ fontWeight: 'bold' }}>Resolution Summary:</Text> {resoDetail || 'N/A'}
                        </Text>
                    )}
                </View>

                <View style={{ backgroundColor: '#fff', borderRadius: 8, shadowColor: '#000', shadowOffset: { width: 0, height: 2 }, shadowOpacity: 0.3, shadowRadius: 4, padding: 16, marginBottom: 16 }}>
                    <Text style={{ fontSize: 20, fontWeight: 'bold', color: '#333', marginBottom: 8 }}>Driver Information</Text>
                    <Text style={{ color: '#555' }}>
                        <Text style={{ fontWeight: 'bold' }}>Driver Name:</Text> {complaint.driver?.driverName || 'Driver not registered.'}
                    </Text>
                    
                    <Text style={{ color: '#555' }}>
                        <Text style={{ fontWeight: 'bold' }}>Tin Plate:</Text> {complaint.driver?.tinPlate || 'Driver not registered.'}
                    </Text>
                </View>

                <View style={{ backgroundColor: '#fff', borderRadius: 8, shadowColor: '#000', shadowOffset: { width: 0, height: 2 }, shadowOpacity: 0.3, shadowRadius: 4, padding: 16 }}>
                    <Text style={{ fontSize: 20, fontWeight: 'bold', color: '#333', marginBottom: 8 }}>Complaint Details</Text>
                    <Text style={{ color: '#555', marginBottom: 8 }}>
                        <Text style={{ fontWeight: 'bold' }}>Violation:</Text> {violationName}
                    </Text>
                    <Text style={{ color: '#555', marginBottom: 8 }}>
                        <Text style={{ fontWeight: 'bold' }}>Location:</Text> {complaint.location || 'N/A'}
                    </Text>
                    <Text style={{ color: '#555', marginBottom: 8 }}>
                        <Text style={{ fontWeight: 'bold' }}>Description:</Text> {complaint.description || 'N/A'}
                    </Text>
                </View>
            </View>
        </ScrollView>
    );
};

export default NotificationInfo;
