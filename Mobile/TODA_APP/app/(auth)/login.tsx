import React, { useState } from 'react';
import { Pressable, Text, View, StyleSheet, TextInput, Alert, ActivityIndicator, Image } from "react-native";
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { API_BASE_URL } from './../../config/config';
import logo from './../../assets/logo.png';
import colors from './../../components/colors';
import { Entypo } from '@expo/vector-icons';

export default function Login({ navigation }: any) {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [loading, setLoading] = useState(false); // Loading state
    const [showPassword, setShowPassword] = useState(false); 

    const handleLogin = async () => {
        setLoading(true);
        try {
            const response = await axios.post(`${API_BASE_URL}/login`, {
                email: email,
                password: password,
            });
    
            setLoading(false);
    
            if (response.data.success) {
                const user = response.data.user;
    
                // Store user data in AsyncStorage
                await AsyncStorage.setItem('user', JSON.stringify(user));
    
                // Check for incomplete profile
                if (
                    user.firstName === 'await' &&
                    user.lastName === 'await' &&
                    user.phoneNumber === 'await' &&
                    user.address === 'await' &&
                    user.age === 0
                ) {
                    navigation.navigate('SignUpStepTwo', { email, password });
                } else if (user.validID === 'await') {
                    // Navigate to the camera for valid ID capture if validID is 'await'
                    navigation.navigate('firstIDCamera', { email });
                } else {
                    Alert.alert('Success', response.data.message);
                    navigation.navigate('Home');
                }
            } else {
                Alert.alert('Login Failed', response.data.message);
            }
        } catch (error: any) {
            setLoading(false);
    
            if (error.response) {
                const { status, data } = error.response;
                if (status === 404) {
                    Alert.alert('Invalid Email', 'User not found. Please check your email.');
                } else if (status === 401) {
                    Alert.alert('Wrong Password', 'Invalid password. Please try again.');
                } else {
                    Alert.alert('Error', data.message || 'An error occurred. Please try again later.');
                }
            } else {
                Alert.alert('Error', 'An error occurred. Please check your internet connection.');
            }
        }
    };
    
    
    

    return (
        <View 
            className="p-3 flex-1 pt-20"
            style={{ backgroundColor: colors.merlot[100] }}
        >
            <Image
                className="items-center justify-center self-center"
                source={logo}
                style={{ width: 150, height: 100 }}
            />

            <Text className='font-bold text-3xl p-5 self-center'>Please, Log In.</Text>
            <TextInput
                className='bg-white p-5 rounded-full my-2'
                placeholder='Email'
                value={email}
                onChangeText={setEmail}
            />
            <View className="relative">
                {/* Password Input and Eye Icon */}
                    <TextInput
                    className="bg-white p-5 rounded-full my-2"
                    placeholder="Password"
                    secureTextEntry={!showPassword}
                    value={password}
                    onChangeText={setPassword}
                    style={{ paddingRight: 40 }} // Add padding to prevent text from overlapping with the eye icon
                    />
                    <Pressable
                    onPressIn={() => setShowPassword(true)} // Show password when pressed
                    onPressOut={() => setShowPassword(false)} // Hide password when released
                    className="absolute right-4 top-1/2 transform -translate-y-1/2"
                    >
                    <Entypo
                        name={showPassword ? "eye" : "eye-with-line"}
                        size={20}
                        color="black"
                    />
                    </Pressable>
                </View>
            <Pressable
                className="self-center w-full p-5 my-2 mt-4 rounded-full"
                style={{ backgroundColor: colors.merlot[700] }}
                onPress={handleLogin}
            >
                <Text className="self-center text-white font-semibold">LOGIN</Text>
            </Pressable>
            
            <View 
                className='w-10/12 flex-row justify-center self-center pt-3'  
            >
                <Text>Don't have an account?</Text>
                <Pressable
                className="px-1"
                onPress={() => navigation.navigate('SignUp')}
                >
                <Text 
                    className="text-white font-semibold px-1"
                    style={{color: colors.merlot[800]}}
                >
                    SIGN UP</Text>
                </Pressable>
            </View>

            {loading && (
                <View className="absolute inset-0 bg-slate-100 opacity-85 justify-center items-center">
                    <ActivityIndicator size="large" color="#ffffff" />
                    <Text className="text-black font-bold text-lg mt-4">Please wait...</Text>
                </View>
            )}
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flexDirection: 'row',
        alignItems: 'center',
        marginVertical: 30,
    },
    line: {
        flex: 1,
        height: 1,
        backgroundColor: 'black',
    },
    text: {
        marginHorizontal: 10,
        fontSize: 16,
        color: 'black',
    },
});
