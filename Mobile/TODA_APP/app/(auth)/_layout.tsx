import * as React from 'react';
import { createStackNavigator } from '@react-navigation/stack';
import Login from './login';
import SignUp from './signup';
import AuthenticatedHome from './authenticatedHome';
import VerifyEmail from './emailVerification';
import SignUpStepTwo from './signupstep2';
import GovertmentID from './firstIDCamera';
import firstIDCamera from './firstIDCamera'

const Stack = createStackNavigator();

export default function AuthLayout() {
  return (
    <Stack.Navigator screenOptions={{headerShown: false}}>
        <Stack.Screen name="Login" component={Login} />
        <Stack.Screen name="SignUp" component={SignUp} />
        <Stack.Screen name="firstIDCamera" component={firstIDCamera} />
        <Stack.Screen name="Home" component={AuthenticatedHome} />
        <Stack.Screen name="VerifyEmail" component={VerifyEmail}/>
        <Stack.Screen name="SignUpStepTwo" component={SignUpStepTwo}/>
        <Stack.Screen name="GovtIDCamera" component={GovertmentID}/>
    </Stack.Navigator>
  );
}
