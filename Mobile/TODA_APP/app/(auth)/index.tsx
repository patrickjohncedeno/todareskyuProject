// index.tsx
import 'react-native-gesture-handler';
import * as React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import AuthLayout from './_layout';

export default function App() {
  return (
    <NavigationContainer>
      <AuthLayout />
    </NavigationContainer>
  );
}
