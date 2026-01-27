
import { initializeApp } from 'firebase/app';
import { getMessaging, getToken, onMessage } from 'firebase/messaging';

// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyBpyCu8DzHm-sEV8vQWeOpvELMKwEeaBAI",
    authDomain: "dohelp-7d140.firebaseapp.com",
    projectId: "dohelp-7d140",
    storageBucket: "dohelp-7d140.firebasestorage.app",
    messagingSenderId: "228064919901",
    appId: "1:228064919901:web:b6d1f42822b129419ea8b4",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

// Initialize Firebase Cloud Messaging and get a reference to the service
const messaging = getMessaging(app);

// Get device token
export async function getDeviceToken() {
    try {
        const currentToken = await getToken(messaging, {
            vapidKey: 'YOUR_VAPID_KEY_HERE' // Replace with your actual VAPID key
        });

        if (currentToken) {
            console.log('Registration token available:', currentToken);
            return currentToken;
        } else {
            console.log('No registration token available. Request permission to generate one.');
            return null;
        }
    } catch (err) {
        console.log('An error occurred while retrieving token. ', err);
        return null;
    }
}

// Handle incoming messages when the app is in foreground
export function onMessageListener() {
    return new Promise((resolve) => {
        onMessage(messaging, (payload) => {
            console.log('Message received. ', payload);
            resolve(payload);
        });
    });
}
