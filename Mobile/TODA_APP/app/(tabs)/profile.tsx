import { 
    Text, 
    View, 
    Image, 
    Pressable, 
    StyleSheet, 
    ActivityIndicator 
} from 'react-native';
import React, { useEffect, useState } from 'react';
import FontAwesome5 from '@expo/vector-icons/FontAwesome5';
import MaterialCommunityIcons from '@expo/vector-icons/MaterialCommunityIcons';
import MaterialIcons from '@expo/vector-icons/MaterialIcons';
import Octicons from '@expo/vector-icons/Octicons';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';

export default function Profile() {
    const [user, setUser] = useState<any>(null);
    const [loading, setLoading] = useState(false); // Loading state
    const router = useRouter();

    useEffect(() => {
        const fetchUserData = async () => {
            try {
                const userData = await AsyncStorage.getItem('user');
                if (userData) {
                    setUser(JSON.parse(userData));
                }
            } catch (error) {
                console.error('Failed to fetch user data', error);
            }
        };

        fetchUserData();
    }, []);

    if (!user) {
        return (
            <View style={styles.container}>
                <Text>Loading...</Text>
            </View>
        );
    }

    const handleProfileInformationPress = () => {
        console.log('Navigating to Profile Information');
        router.push({
            pathname: '/profileSettings/AccountPage/profInfo',
        });
    };

    const handleSecuritySettingsPress = () => {
        console.log('Navigating to Security Settings');
        router.push({
            pathname: '/profileSettings/AccountPage/secSet',
        });
    };

    const handleLogoutPress = async () => {
        setLoading(true); // Show loading indicator
        try {
            await AsyncStorage.clear(); // Clear all AsyncStorage data
            setTimeout(() => {
                setLoading(false); // Hide loading indicator
                router.replace('/(auth)/login'); // Navigate to the signup screen
            }, 2000); // Optional delay for UX
        } catch (error) {
            console.error('Failed to clear AsyncStorage:', error);
            setLoading(false); // Ensure loading stops in case of error
        }
    };

    return (
        <View className="flex-1">
            

            <View
                className="h-72 rounded-b-2xl mt-0"
                style={{ backgroundColor: '#8B0000' }}      
            >
                <View
                    className="p-5 pt-16 flex-row justify-between"
                >
                    <Text className="font-bold text-3xl color-white">
                        Profile
                    </Text>
                </View>
            </View>

            <View 
                className="bg-blue-950 absolute rounded-full self-center"
                style={{ top: 60, zIndex: 2 }}
            >
                <Image
                    source={require('~/assets/pfp.png')} 
                    className="h-36 aspect-square rounded-full"
                />
            </View>

            <View
                className="bg-white w-11/12 self-center rounded-2xl p-4 absolute"
                style={{ height: 693, top: 130, zIndex: 1 }}
            >
                <Text className="self-center font-semibold text-2xl mt-14 pb-4">
                    {user.firstName} {user.lastName}
                </Text>

                <View className="gap-1 pb-4">
                    <Text className="font-extrabold text-xl">
                        Account
                    </Text>
                    <Text className="text-sm color-slate-500">
                        Manage your personal information and security settings.
                    </Text>
                </View>

                <View className="bg-slate-100 rounded-lg py-3 mb-4">
                    <Pressable 
                        className="flex-row items-center py-2 px-3 justify-between"
                        onPress={handleProfileInformationPress}
                    >
                        <View className="flex-row items-center gap-5">
                            <View className="w-8 items-center">
                                <FontAwesome5 name="user" size={15} color="black" />
                            </View>
                            <Text className="text-base font-semibold">
                                Profile Information
                            </Text>
                        </View>
                        <MaterialIcons name="keyboard-arrow-right" size={20} color="black" />
                    </Pressable>

                    <Pressable 
                        className="flex-row items-center py-2 px-3 justify-between"
                        onPress={handleSecuritySettingsPress}
                    >
                        <View className="flex-row items-center gap-5">
                            <View className="w-8 items-center">\
                                <Octicons name="stack" size={15} color="black" />
                                {/* <FontAwesome5 name="user" size={15} color="black" /> */}
                            </View>
                            <Text className="text-base font-semibold">
                                My Complaints
                            </Text>
                        </View>
                        <MaterialIcons name="keyboard-arrow-right" size={20} color="black" />
                    </Pressable>
                </View>

                <View className="gap-1 pb-4">
                    <Text className="font-extrabold text-xl">
                        Help
                    </Text>
                    <Text className="text-sm color-slate-500">
                        Find answers and contact support.
                    </Text>
                </View>

                <View className="bg-slate-100 rounded-lg py-3 mb-4">
                    <Pressable 
                        className="flex-row items-center py-2 px-3 justify-between"
                        onPress={() => console.log('Navigating to FAQ')}
                    >
                        <View className="flex-row items-center gap-5">
                            <View className="w-8 items-center">
                                <MaterialCommunityIcons name="frequently-asked-questions" size={15} color="black" />
                                {/* <FontAwesome5 name="user" size={15} color="black" /> */}
                            </View>
                            <Text className="text-base font-semibold">
                                Frequently Asked Questions
                            </Text>
                        </View>
                        <MaterialIcons name="keyboard-arrow-right" size={20} color="black" />
                    </Pressable>
                </View>

                <Pressable 
                    className="flex-row items-center px-3 justify-end"
                    onPress={handleLogoutPress}
                >
                    <View className="flex-row items-center gap-2 pt-3">
                        <View className="w-8 items-center">
                            <MaterialCommunityIcons name="logout" size={20} color="black" />
                        </View>
                        <Text className="text-xl font-semibold">
                            Logout
                        </Text>
                    </View>
                </Pressable>

            </View>
            
            {loading && (
            <View className="absolute inset-0 bg-slate-100 opacity-85 justify-center items-center" style={{ zIndex: 3 }}>
                <ActivityIndicator size="large" color="#ffffff" />
                <Text className="text-black font-bold text-lg mt-4">Please wait...</Text>
            </View>
        )}
        </View>

        
        
        
        
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
    },
});
