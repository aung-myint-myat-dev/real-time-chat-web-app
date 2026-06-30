import { inject, onMounted, provide, ref, watch } from "vue";

const ThemeSymbol = Symbol('Theme');

export function provideTheme () {
    const isDarkMode = ref(false);

    const toggleTheme = () => {
        isDarkMode.value = !isDarkMode.value;
    }
    
    const updateDOM = () => {
        if(typeof window !== 'undefined') {
            if(isDarkMode.value) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }
    }

    watch(isDarkMode, () => {
        updateDOM();
    });

    onMounted(() => {
        if(typeof window !== 'undefined') {
            const savedTheme = localStorage.getItem('theme');
            if(savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                isDarkMode.value = true;
            }

            updateDOM();
        }
    });

    const context = { isDarkMode, toggleTheme };
    provide(ThemeSymbol, context);
    return context;
}

export function useTheme() {
    const themeContext = inject(ThemeSymbol);
    if(!themeContext) {
        throw new Error('useTheme() must be used after provideTheme()');
    }
    return themeContext;
}