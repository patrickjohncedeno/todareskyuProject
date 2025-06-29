import { Pressable, Text, View, TextInput, Alert, ActivityIndicator, StyleSheet  } from 'react-native';
import axios from 'axios';
import { Picker } from '@react-native-picker/picker';
import { API_BASE_URL } from './../../config/config';
import logo from './../../assets/logo.png';
import colors from './../../components/colors';
import React, { useEffect, useState } from 'react';
import { BackHandler } from 'react-native';
import { useFocusEffect } from '@react-navigation/native';

interface Municipality {
  citymunCode: string;
  citymunDesc: string;
}

interface Barangay {
  brgyCode: string;
  brgyDesc: string;
}


export default function SignUpStepTwo({ route, navigation }: any) {

  const { email, password } = route.params; // Retrieve email and password from Step 1
  const [lName, setlName] = useState('');
  const [fName, setfName] = useState('');
  const [phoneNumber, setphoneNumber] = useState('');
  const [age, setAge] = useState('');     
  const ageOptions = Array.from({ length: 67 }, (_, i) => i + 13);
  const [selectedMunicipality, setSelectedMunicipality] = useState('');
  const [selectedBarangay, setSelectedBarangay] = useState('');
  const [loading, setLoading] = useState(false); // Loading state
  const [municipalities, setMunicipalities] = useState<Municipality[]>([]);
  const [barangays, setBarangays] = useState<Barangay[]>([]);
  const selectedBarangayDesc = barangays.find(
    (b) => b.brgyCode === selectedBarangay
  )?.brgyDesc;
  
  const selectedMunicipalityDesc = municipalities.find(
    (m) => m.citymunCode === selectedMunicipality
  )?.citymunDesc;
  
  const fullAddress = `${selectedBarangayDesc || ''}, ${selectedMunicipalityDesc || ''}`;

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


  useEffect(() => {
    axios.get(`${API_BASE_URL}/municipalities`)
      .then((response) => {
        setMunicipalities(response.data);
      })
      .catch((error) => console.log('Error fetching municipalities', error));
  }, []);

  // Fetch barangays when municipality is selected
  const fetchBarangays = (citymunCode: string) => {
    axios.post(`${API_BASE_URL}/barangays`, { citymunCode })
      .then((response) => {
        setBarangays(response.data);
        setSelectedBarangay(''); // Reset barangay selection
      })
      .catch((error) => console.log('Error fetching barangays', error));
  };

  const handleSignUp = async () => {
    setLoading(true); // Start loading

    try {
      const response = await axios.post(`${API_BASE_URL}/complete-profile`, {
        firstName: fName,
        lastName: lName,
        email: email,
        phoneNumber: phoneNumber,
        password: password,
        address: fullAddress,
        age: age,
      });

      Alert.alert('Success', response.data.message);
      setLoading(false); // Stop loading
      navigation.navigate('firstIDCamera', { email });
    } catch (error) {
      setLoading(false); // Stop loading in case of an error
      if (axios.isAxiosError(error) && error.response) {
        const { message } = error.response.data;

        if (message === 'Invalid email address') {
          Alert.alert('Error', 'The email address you provided is invalid. Please check and try again.');
        } else {
          Alert.alert('Error', message || 'Something went wrong. Please try again.');
        }
      } else {
        Alert.alert('Error', 'Unable to reach the server. Please try again later.');
      }
    }
  };

  return (
    <View 
      className="pt-10 p-2 flex-1"
      style={{ backgroundColor: colors.merlot[100] }}
    >
      <Text className="font-bold text-3xl p-5 self-center">Complete Your Profile</Text>

      <View className="flex-row justify-between">
        {/* First Name */}
        <TextInput
          className="bg-white p-5 rounded-lg my-2 w-52"
          placeholder="First Name"
          value={fName}
          onChangeText={setfName}
        />
        {/* Last Name */}
        <TextInput
          className="bg-white p-5 rounded-lg my-2 w-44"
          placeholder="Last Name"
          value={lName}
          onChangeText={setlName}
        />
      </View>

      {/* Address */}
      <View style={styles.container}>
        <Text style={styles.label}>Municipality</Text>
          <Picker
            selectedValue={selectedMunicipality}
            onValueChange={(value) => {
              setSelectedMunicipality(value);
              fetchBarangays(value);
            }}
            style={styles.picker}
          >
            <Picker.Item label="Select Municipality" value="" />
            {municipalities.map((item) => (
              <Picker.Item key={item.citymunCode} label={item.citymunDesc} value={item.citymunCode} />
            ))}
          </Picker>

          <Text style={styles.label}>Barangay</Text>
          <Picker
            selectedValue={selectedBarangay}
            onValueChange={(value) => setSelectedBarangay(value)}
            style={styles.picker}
            enabled={selectedMunicipality !== ''}
          >
            <Picker.Item label="Select Barangay" value="" />
            {barangays.map((item) => (
              <Picker.Item key={item.brgyCode} label={item.brgyDesc} value={item.brgyCode} />
            ))}
          </Picker>
    </View>

      {/* Age */}
      <View className="bg-white rounded-lg mb-4">
        <Picker
          selectedValue={age}
          onValueChange={(value) => setAge(value)}
          className="text-lg p-3"
        >
          <Picker.Item label="Select Age" value="" />
          {ageOptions.map((age) => (
            <Picker.Item key={age} label={`${age}`} value={`${age}`} />
          ))}
        </Picker>
      </View>

      {/* Phone Number */}
      <TextInput
        className="bg-white p-5 rounded-lg my-2"
        placeholder="Phone Number"
        value={phoneNumber}
        onChangeText={setphoneNumber}
      />

      {/* Sign Up Button */}
      <Pressable
        className="self-center w-full p-5 my-2 rounded-full"
        style={{backgroundColor: colors.merlot[800]}}
        onPress={handleSignUp}
        disabled={loading} // Disable button while loading
      >
        <Text className="self-center text-white font-semibold">NEXT</Text>
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
const styles = StyleSheet.create({
  container: { padding: 8 },
  label: { fontSize: 16, marginBottom: 5  },
  picker: { backgroundColor: '#fff', marginBottom: 15 },
});
