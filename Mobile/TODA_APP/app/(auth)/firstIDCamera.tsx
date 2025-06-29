import React, { useState, useEffect } from 'react';
import { View, Text, Button, Image, Alert, StyleSheet, ActivityIndicator, TouchableOpacity } from 'react-native';
import * as ImagePicker from 'expo-image-picker';
import { Camera } from 'expo-camera';
import axios, { AxiosError } from 'axios';
import { API_BASE_URL } from './../../config/config';
import logo from './../../assets/logo.png';
import colors from './../../components/colors';
import { BackHandler } from 'react-native';
import { useFocusEffect } from '@react-navigation/native';


const GovtIDCapture = ({ route, navigation }: any) => {
  const { email } = route.params;
  const [validID, setValidID] = useState<string | null>(null);
  const [permission, setPermission] = useState<boolean | null>(null);
  const [loading, setLoading] = useState<boolean>(false); // Loading state

  useEffect(() => {
    (async () => {
      const { status } = await Camera.requestCameraPermissionsAsync();
      setPermission(status === 'granted');
    })();
  }, []);

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

  const takePicture = async () => {
    if (permission === null) {
      return Alert.alert('No camera permission');
    }

    const result = await ImagePicker.launchCameraAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.Images,
      allowsEditing: true,
      aspect: [4, 3],
      quality: 1,
    });

    if (!result.canceled && result.assets.length > 0) {
      const imageUri = result.assets[0].uri;
      setValidID(imageUri);
    }
  };

  interface ErrorResponse {
    message: string;
  }

  const uploadImage = async () => {
    if (!validID) {
      Alert.alert('Please capture the ID.');
      return;
    }

    const formData = new FormData();

    formData.append('validID', {
      uri: validID,
      type: 'image/jpeg',
      name: 'valid_id.jpg',
    } as any);

    formData.append('email', email);

    setLoading(true); // Start loading
    try {
      const response = await axios.post(`${API_BASE_URL}/upload`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      Alert.alert('Image uploaded successfully', response.data.message);
      navigation.navigate('Login');
    } catch (err) {
      const error = err as AxiosError;

      console.error('Upload error:', error.message);

      if (error.response) {
        const errorData = error.response.data as ErrorResponse;
        Alert.alert('Upload error', `Server Error: ${errorData.message}`);
      } else if (error.request) {
        Alert.alert('Upload error', 'No response received from the server');
      } else {
        Alert.alert('Upload error', 'An error occurred while uploading the image');
      }
    } finally {
      setLoading(false); // Stop loading
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Government ID Capture</Text>

      <View style={styles.section}>
        <Text style={styles.label}>Government ID:</Text>
        {validID && <Image source={{ uri: validID }} style={styles.image} />}
        <TouchableOpacity style={[styles.button, { backgroundColor: colors.merlot[800] }]} onPress={takePicture}>
          <Text style={styles.buttonText}>Capture ID</Text>
        </TouchableOpacity>
      </View>

      {validID && (
        <TouchableOpacity
          style={[
            styles.button,
            {
              backgroundColor: colors.merlot[800], // Visible color when ID is captured
            },
          ]}
          onPress={uploadImage}
        >
          <Text style={styles.buttonText}>Upload Image</Text>
        </TouchableOpacity>
      )}

      {/* Full-Screen Loading Indicator */}
      {loading && (
        <View style={styles.loadingOverlay}>
          <ActivityIndicator size="large" color="#000" />
          <Text style={styles.loadingText}>Please wait...</Text>
        </View>
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    padding: 20,
    backgroundColor: colors.merlot[100],
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    marginBottom: 20,
  },
  section: {
    marginBottom: 30,
    alignItems: 'center',
  },
  label: {
    fontSize: 18,
    marginBottom: 10,
  },
  image: {
    width: 200,
    height: 150,
    borderRadius: 10,
    marginBottom: 10,
  },
  button: {
    padding: 15,
    borderRadius: 10,
    alignItems: 'center',
    marginVertical: 10,
  },
  buttonText: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 16,
  },
  loadingOverlay: {
    position: 'absolute',
    top: 0,
    bottom: 0,
    left: 0,
    right: 0,
    backgroundColor: 'rgba(0, 0, 0, 0.7)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  loadingText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
    marginTop: 10,
  },
});

export default GovtIDCapture;
