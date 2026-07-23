export function formatConversationTime(isoString) {
    if (!isoString) {
        return "";
    }

    const date = new Date(isoString);

    if (Number.isNaN(date.getTime())) {
        return "";
    }

    const now = new Date();
    const isSameDay = (a, b) =>
        a.getFullYear() === b.getFullYear() &&
        a.getMonth() === b.getMonth() &&
        a.getDate() === b.getDate();

    const yesterday = new Date(now);
    yesterday.setDate(now.getDate() - 1);

    if (isSameDay(date, now)) {
        return date.toLocaleTimeString([], {
            hour: "numeric",
            minute: "2-digit",
        });
    }

    if (isSameDay(date, yesterday)) {
        return "Yesterday";
    }

    return date.toLocaleDateString([], {
        month: "short",
        day: "numeric",
    });
}
