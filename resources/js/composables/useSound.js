import { ref } from 'vue';

export function useNotificationSound() {
    const audio = new Audio('/sounds/primary-noti.wav');
    audio.volume = 0.5;

    const playSound = () => {
        audio.currentTime = 0;
        audio.play().catch((error) => {
            console.warn('Audio play blocked by browser interaction policy:', error);
        });
    };

    return {
        playSound,
    };
}