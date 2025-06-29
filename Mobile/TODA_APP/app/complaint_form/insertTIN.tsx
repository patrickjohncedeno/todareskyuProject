import React, { useState, useRef } from 'react';
import { View, TextInput, StyleSheet, Text, TouchableOpacity, Modal, ActivityIndicator, Image } from 'react-native';
import { useRouter } from 'expo-router';
import { API_BASE_URL } from './../../config/config';
import colors from './../../components/colors';
import mtop from './../../assets/mtop.jpg';

const NumberInput = () => {
    const [numbers, setNumbers] = useState<string[]>(['', '', '', '']);
    const [loading, setLoading] = useState(false);
    const [modalVisible, setModalVisible] = useState(false);
    const [message, setMessage] = useState('');
    const inputRefs = useRef<(TextInput | null)[]>([]);
    const router = useRouter();

    const handleInputChange = (text: string, index: number) => {
        const newNumbers = [...numbers];
        newNumbers[index] = text.replace(/[^0-9]/g, '');
        setNumbers(newNumbers);

        if (index < numbers.length - 1 && newNumbers[index].length === 1) {
            inputRefs.current[index + 1]?.focus();
        }

        if (index > 0 && newNumbers[index].length === 0) {
            inputRefs.current[index - 1]?.focus();
        }
    };

    const isComplete = numbers.every(number => number.length === 1);

    const handleSearch = async () => {
        const tinPlate = numbers.join('');
        console.log('Searching for:', tinPlate);
        setModalVisible(true);
        setLoading(true);
        setMessage(`Searching for driver information with Tin Plate: ${tinPlate}`);
    
        try {
            const response = await fetch(`${API_BASE_URL}/driver/${tinPlate}`);
            
            // Check if response is OK (status code 200)
            if (response.ok) {
                const data = await response.json();
                setMessage(`Driver information found for Tin Plate: ${tinPlate}.`);
                console.log('Search result:', data);
                router.push({
                    pathname: '/complaint_form/complaint_reg',
                    params: { driverData: JSON.stringify(data) },
                });
            } else {
                const errorData = await response.json();
                setMessage(`No driver information with Tin Plate: ${tinPlate}. Please try another one.`);
            }
        } catch (error) {
            console.error('Error fetching data:', error);
            setMessage('An error occurred while searching. Please try again.');
        } finally {
            setLoading(false);
        }
    };
    


    const closeModal = () => {
        setModalVisible(false);
    };

    return (
        <View style={styles.container}>
            <Text style={styles.title}>Enter MTOP of the Tricycle</Text>
            <Text className='pb-3'>Sample MTOP of a Tricycle</Text>
            <Image
                className="items-center justify-center self-center"
                source={mtop}
                style={{ width: 140, height: 80, marginBottom:20 }}
            />
            <View style={styles.inputContainer}>
                {numbers.map((number, index) => (
                    <TextInput
                        key={index}
                        style={styles.input}
                        value={number}
                        onChangeText={(text) => handleInputChange(text, index)}
                        keyboardType="numeric"
                        maxLength={1}
                        ref={(ref) => inputRefs.current[index] = ref}
                    />
                ))}
            </View>
            <TouchableOpacity 
                onPress={handleSearch} 
                disabled={!isComplete}
                style={{
                    backgroundColor: isComplete ? colors.merlot[700] : '#ccc',
                    borderRadius: 10,
                    padding: 10,
                    marginTop: 30,
                }}
            >
                <Text style={{ color: isComplete ? 'white' : 'darkgray' }}>
                    Search Tricycle Information
                </Text>
            </TouchableOpacity>

            {/* Modal for loading and messages */}
            <Modal
                transparent={true}
                visible={modalVisible}
                animationType="fade"
            >
                <View style={styles.modalContainer}>
                    <View style={styles.modalContent}>
                        <TouchableOpacity onPress={closeModal} style={styles.closeButton}>
                            <Text style={styles.closeButtonText}>x</Text>
                        </TouchableOpacity>
                        <Text style={styles.modalText}>
                            {loading ? 
                                `Searching for driver information with Tin Plate: ${numbers.join('')}` :
                                message
                            }
                        </Text>
                        {loading && <ActivityIndicator size="large" color="#007BFF" />}
                    </View>
                </View>
            </Modal>
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        padding: 20,
        backgroundColor: '#f5f5f5',
    },
    title: {
        fontSize: 18,
        marginBottom: 20,
        fontWeight: 'bold',
    },
    inputContainer: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        width: '80%',
    },
    input: {
        width: 50,
        height: 50,
        borderColor: colors.merlot[700],
        borderWidth: 2,
        borderRadius: 10,
        textAlign: 'center',
        fontSize: 24,
        marginHorizontal: 5,
    },
    modalContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: 'rgba(0, 0, 0, 0.5)', // Semi-transparent background
    },
    modalContent: {
        width: 300,
        padding: 20,
        backgroundColor: 'white',
        borderRadius: 10,
        alignItems: 'center',
        justifyContent: 'center',
    },
    modalText: {
        marginBottom: 20,
        fontSize: 16,
        textAlign: 'center',
    },
    closeButton: {
        position: 'absolute',
        top: 4,
        right: 10,
    },
    closeButtonText: {
        fontSize: 20,
        color: 'black',
    },
});

export default NumberInput;
