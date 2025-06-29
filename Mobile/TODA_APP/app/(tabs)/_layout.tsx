import { Tabs, useRouter } from "expo-router"; // Import useRouter from expo-router
import Ionicons from '@expo/vector-icons/Ionicons';
import FontAwesome from '@expo/vector-icons/FontAwesome';
import { TouchableOpacity, StyleSheet, Text } from 'react-native';
import colors from './../../components/colors';
import React from "react";

export default function TabsLayout() {
    const router = useRouter(); // Initialize the router

    const handleReportRegisteredVehicle = () => {
        // Use router to navigate to the complaint registration form
        router.push('/complaint_form/complaint_reg'); // Adjust this path as necessary
    };

    return (
        <>
            <Tabs
                screenOptions={({ route }) => ({
                    tabBarActiveTintColor: 'black',
                    tabBarInactiveTintColor: 'gray',
                    tabBarShowLabel: true,
                    headerShown: false,
                    headerStyle: {
                        backgroundColor: colors.merlot[800],
                    },
                    headerTitleStyle: {
                        color: 'white',
                    },
                    tabBarIcon: ({ color }) => {
                        let iconName: string;
                        let iconLibrary: 'FontAwesome' | 'Ionicons';

                        if (route.name === 'index') {
                            iconName = 'home';
                            iconLibrary = 'FontAwesome';
                        } else if (route.name === 'profile') {
                            iconName = 'user';
                            iconLibrary = 'FontAwesome';
                        } else if (route.name === 'scan') {
                            iconName = 'scan-circle';
                            iconLibrary = 'Ionicons';
                        } else {
                            iconName = 'circle';
                            iconLibrary = 'FontAwesome';
                        }

                        if (iconLibrary === 'FontAwesome') {
                            return <FontAwesome name={iconName as keyof typeof FontAwesome.glyphMap} size={24} color={color} />;
                        } else if (iconLibrary === 'Ionicons') {
                            return <Ionicons name={iconName as keyof typeof Ionicons.glyphMap} size={28} color={color} />;
                        }
                    },
                    tabBarLabel: ({ focused }) =>
                        focused ? (
                            <Text
                                style={{
                                    fontSize: 12,
                                    fontWeight: 'bold',
                                    color: 'black',
                                }}
                            >
                                {route.name === 'index' ? 'Home' : route.name.charAt(0).toUpperCase() + route.name.slice(1)}
                            </Text>
                        ) : null,
                })}
            >
                {/* Your other tabs go here */}

            </Tabs>
        </>
    );
}

const styles = StyleSheet.create({
    reportButton: {
        color: 'blue', // Style it as needed
        padding: 10,
        textAlign: 'center',
    },
});
