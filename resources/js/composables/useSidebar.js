import { inject, provide, ref } from "vue"

const ChatSidebarSymbol = Symbol('ChatSidebar')

export function provideSidebar () {
    const isOpen = ref(false);

    const toggleChatSidebar = () => {
        isOpen.value = !isOpen.value;
    }

    const context = { isOpen, toggleChatSidebar };
    provide(ChatSidebarSymbol, context);
    return context;
}

export function useSidebar() {
    const sidebarContext = inject(ChatSidebarSymbol);
    if(!sidebarContext) throw new Error('useSidebar() must be used after provideSidebar()');
    return sidebarContext;
}