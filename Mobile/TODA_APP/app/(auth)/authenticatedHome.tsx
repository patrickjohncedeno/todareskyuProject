import React from 'react';
import { Redirect } from 'expo-router';

const AuthenticatedHome = () => {
    return <Redirect href="/(tabs)" />;
};

export default AuthenticatedHome;
