import React, { useState } from 'react';
import { Pressable, Text, View, TextInput, Alert, ActivityIndicator, StyleSheet } from 'react-native';
import axios from 'axios';
import { API_BASE_URL } from './../../config/config';
import logo from './../../assets/logo.png';
import colors from './../../components/colors';
import { BackHandler } from 'react-native';
import { useFocusEffect } from '@react-navigation/native';

export default function VerifyEmail({ route, navigation }: any) {
  const { email, password } = route.params; // Retrieve email and password from Step 1
  const [verificationCode, setVerificationCode] = useState('');
  const [loading, setLoading] = useState(false); // Loading state

  useFocusEffect(
    React.useCallback(() => {
      const onBackPress = () => {
        navigation.navigate('Login'); // Navigate to Login screen
        return true; // Prevent default back button behavior
      };

      BackHandler.addEventListener('hardwareBackPress', onBackPress);

      return () => BackHandler.removeEventListener('hardwareBackPress', onBackPress);
    }, [navigation])
  );


  const handleVerifyCode = async () => {
    setLoading(true); // Start loading

    try {
      const response = await axios.post(`${API_BASE_URL}/verify-code`, {
        email: email,
        code: verificationCode,
      });

      if (response.data.success) {
        Alert.alert('Success', 'Verification successful!');
        setLoading(false); // Stop loading
        navigation.navigate('SignUpStepTwo', { email, password });
      }
    } catch (error) {
      setLoading(false); // Stop loading in case of an error
      if (axios.isAxiosError(error) && error.response) {
        const { message } = error.response.data;

        if (message === 'Invalid email address') {
          Alert.alert('Error', 'The email address you entered is invalid. Please try again.');
        } else if (message === 'Verification code expired') {
          Alert.alert('Error', 'The verification code has expired. Please request a new one.');
        } else if (message === 'Invalid verification code') {
          Alert.alert('Error', 'The verification code you entered is invalid.');
        } else {
          Alert.alert('Error', 'Something went wrong. Please try again.');
        }
      } else {
        Alert.alert('Error', 'Unable to reach the server. Please try again later.');
      }
    }
  };

  return (
    <View 
      className="pt-10 p-2 bg-slate-300 flex-1"
      style={{ backgroundColor: colors.merlot[100] }}
    >
      <Text className="font-bold text-3xl p-5 self-center">Verify Your Email</Text>

      <Text className="text-center p-5">
        We have sent a verification code to your email. Please enter it below to proceed.
      </Text>

      {/* Verification Code Input */}
      <TextInput
        className="bg-white p-5 rounded-full my-2"
        placeholder="Enter Verification Code"
        keyboardType="numeric" // Ensure numeric input
        value={verificationCode}
        onChangeText={setVerificationCode}
      />

      {/* Verify Button */}
      <Pressable
        className="self-center w-full p-5 my-2 mt-4 rounded-full"
        style={{backgroundColor: colors.merlot[800]}}
        onPress={handleVerifyCode}
        disabled={loading} // Disable button when loading
      >
        <Text className="self-center text-white font-semibold">VERIFY</Text>
      </Pressable>

      {/* Full-Screen Loading Indicator */}
      {loading && (
        <View className="absolute inset-0 bg-slate-100 opacity-85 justify-center items-center">
          <ActivityIndicator size="large" color="#000" />
          <Text className="text-black font-bold text-lg mt-4">Please wait...</Text>
        </View>
      )}
    </View>
  );
}
