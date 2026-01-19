import './bootstrap';
import { getDeviceToken } from './firebase';

window.addEventListener('load', async () => {
    const token = await getDeviceToken();
    if (token) {
        const deviceTokenInput = document.getElementById('device_token');
        if (deviceTokenInput) {
            deviceTokenInput.value = token;
        }
    }
});
