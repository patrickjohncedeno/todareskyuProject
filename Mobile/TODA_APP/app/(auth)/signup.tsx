import React, { useState } from 'react';
import { Pressable, Text, View, TextInput, Alert, ActivityIndicator, Image } from 'react-native';
import axios from 'axios';
import { API_BASE_URL } from './../../config/config';
import { Entypo } from '@expo/vector-icons';
import { FontAwesome } from '@expo/vector-icons'; // For check icons
import logo from './../../assets/logo.png';
import colors from './../../components/colors';

export default function SignUpStepOne({ navigation }: any) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [loading, setLoading] = useState(false); // Loading state
  const [isPasswordFocused, setIsPasswordFocused] = useState(false);
  const [showPassword, setShowPassword] = useState(false); 
  const [showCurrentPassword, setShowCurrentPassword] = useState(false);


  const handleGoToLogin = () => {
    navigation.navigate('Login'); // Assume 'Login' is the name of the login screen in your navigator
  };
  
  // Password validation rules
  const passwordRules = [
    { label: 'At least 8 characters', valid: password.length >= 8 },
    { label: 'At least one number', valid: /\d/.test(password) },
    { label: 'At least one lowercase letter', valid: /[a-z]/.test(password) },
    { label: 'At least one uppercase letter', valid: /[A-Z]/.test(password) },
    { label: 'At least one special character', valid: /[!@#$%^&*(),.?":{}|<>]/.test(password) },
  ];

  const allRulesValid = passwordRules.every((rule) => rule.valid);

  const handleNextStep = async () => {
    if (password !== confirmPassword) {
      Alert.alert('Error', 'Passwords do not match.');
      return;
    }

    if (!allRulesValid) {
      Alert.alert('Error', 'Please ensure the password meets all the requirements.');
      return;
    }

    setLoading(true); // Start loading prompt

    try {
      const response = await axios.post(`${API_BASE_URL}/register-email`, {
        email: email,
        password: password,
        password_confirmation: confirmPassword, // Send the confirmation as well
      });

      // When registration is successful, show success alert and navigate to 'VerifyEmail' screen
      Alert.alert('Success', 'Verification code sent to your email!');
      setLoading(false); // Stop loading
      navigation.navigate('VerifyEmail', { email }); // Pass email for the next step
    } catch (error) {
      setLoading(false); // Stop loading in case of error
      if (axios.isAxiosError(error)) {
        console.error('Axios error:', error.response?.data);

        // Handle specific error messages
        if (error.response?.data?.errors) {
          const errorMessages = Object.values(error.response.data.errors).flat();
          Alert.alert('Error', errorMessages.join(', '));
        } else {
          Alert.alert('Error', error.response?.data?.message || 'Something went wrong. Please try again.');
        }
      } else {
        console.error('Unexpected error:', error);
        Alert.alert('Error', 'An unexpected error occurred. Please try again.');
      }
    }
  };

  return (
    <View className="pt-20 p-2 flex-1  relative" style={{ backgroundColor: colors.merlot[100] }}>
      <Image
        className="items-center justify-center self-center"
        source={logo}
        style={{ width: 150, height: 100 }}
      />

      <Text className="font-bold text-3xl p-5 self-center">Create Your Account</Text>

      {/* Email Input */}
      <TextInput
        className="bg-white p-5 rounded-full my-2"
        placeholder="Email"
        value={email}
        onChangeText={setEmail}
      />

<View className="relative">
  <View className="relative">
    {/* Password Input and Eye Icon */}
    <TextInput
      className="bg-white p-5 rounded-full my-2"
      placeholder="Password"
      secureTextEntry={!showPassword}
      value={password}
      onChangeText={setPassword}
      onFocus={() => setIsPasswordFocused(true)} // Show rules when focused
      onBlur={() => setIsPasswordFocused(false)} // Hide rules when blurred
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

  {/* Password Rules or Success Message */}
  {isPasswordFocused && (
    <View className="mt-2">
      {!allRulesValid ? (
        passwordRules.map((rule, index) => (
          <View key={index} className="flex-row items-center mb-1 ps-4">
            <FontAwesome
              name={rule.valid ? "check-circle" : "circle"}
              size={20}
              color={rule.valid ? "green": colors.merlot[700]}
              className="mr-2"
            />
            <Text
              className={rule.valid ? "text-green-600" : "text-red-600"}
            >
              {rule.label}
            </Text>
          </View>
        ))
      ) : (
        <Text className="text-green-600 font-bold mt-1 mb-2 ps-4">
          <FontAwesome
            name="check-circle"
            size={20}
            color="green"
          />{" "}
          Password meets all requirements!
        </Text>
      )}
    </View>
  )}
</View>

    

      {/* Confirm Password */}
      <View
        className='relative'
      >
        <TextInput
          className="bg-white p-5 rounded-full my-2"
          placeholder="Confirm Password"
          secureTextEntry={!showCurrentPassword}
          value={confirmPassword}
          onChangeText={setConfirmPassword}
        />
        <Pressable 
            onPressIn={() => setShowCurrentPassword(true)} // Show password when pressed
            onPressOut={() => setShowCurrentPassword(false)} // Hide password when released
            className="absolute right-4 top-1/2 transform -translate-y-1/2"
          >
            <Entypo 
              name={showPassword ? "eye" : "eye-with-line"} 
              size={20} 
              color="black" 
            />
          </Pressable>
        </View>
      {/* Next Button */}
      <Pressable
        className="self-center w-full p-5 my-2 mt-4 rounded-full"
        style={{ backgroundColor: colors.merlot[700] }}
        onPress={handleNextStep}
        disabled={loading}
      >
        <Text className="self-center text-white font-semibold">NEXT</Text>
      </Pressable>
      <View 
        className='w-10/12 flex-row justify-center self-center pt-3'  
      >
        <Text>Already have an account?</Text>
        <Pressable
          className="px-1"
          onPress={handleGoToLogin}  // Navigate to login screen
        >
          <Text 
            className="text-white font-semibold px-1"
            style={{color: colors.merlot[800]}}
          >
              LOGIN</Text>
        </Pressable>
      </View>

      {/* Full-Screen Loading Indicator */}
      {loading && (
        <View className="absolute inset-0 bg-slate-100 opacity-85 justify-center items-center">
          <ActivityIndicator size="large" color="#ffffff" />
          <Text className="text-black font-bold text-lg mt-4">Please wait...</Text>
        </View>
      )}
    </View>
  );
}
