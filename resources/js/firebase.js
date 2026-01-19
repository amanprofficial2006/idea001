import { initializeApp } from 'firebase/app';
import { getMessaging, getToken, onMessage } from 'firebase/messaging';

const firebaseConfig = {
  apiKey: "AIzaSyBpyCu8DzHm-sEV8vQWeOpvELMKwEeaBAI",
  authDomain: "dohelp-7d140.firebaseapp.com",
  projectId: "dohelp-7d140",
  storageBucket: "dohelp-7d140.firebasestorage.app",
  messagingSenderId: "228064919901",
  appId: "1:228064919901:web:b6d1f42822b129419ea8b4",
  measurementId: "G-61V67TYW8N",
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

export async function getDeviceToken() {
    try {
        const token = await getToken(messaging, {
            vapidKey: 'BIWhgwdKKixoudiW4sKuSMi_eMpE1r4JxQpePCraP2i8O6XiEJbkkoL_CtSio5J4omUl8_pFliP2l2vN4qR9U-U'
        });
        return token;
    } catch (error) {
        console.error('Error getting FCM token:', error);
        return null;
    }
}

// Handle foreground messages (when tab is active)
// Only log the message, do not show duplicate notification
onMessage(messaging, (payload) => {
    console.log('Foreground message received:', payload);
    // ❌ Removed: new Notification(...) to prevent duplicate notifications
});
