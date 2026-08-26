import React, { useEffect } from 'react'
import { useToast } from '../Hooks/useToast'

function ToastItem({ id, type, message, onDismiss }) {
    useEffect(() => {
        const timer = setTimeout(onDismiss, 5000)
        return () => clearTimeout(timer)
    }, [onDismiss])

    const bgColor = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500',
    }[type] || 'bg-gray-500'

    const icon = {
        success: '✓',
        error: '✕',
        warning: '⚠',
        info: 'ℹ',
    }[type] || '•'

    return (
        <div
            className={`${bgColor} text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in`}
            role="alert"
        >
            <span className="text-lg font-bold">{icon}</span>
            <span className="flex-1">{message}</span>
            <button
                onClick={onDismiss}
                className="text-white/70 hover:text-white font-bold text-lg leading-none"
                aria-label="Close notification"
            >
                ×
            </button>
        </div>
    )
}

export default function Toast() {
    const { toasts, dismiss } = useToast()

    if (toasts.length === 0) return null

    return (
        <div className="fixed top-4 right-4 z-50 space-y-2">
            <style>{`
                @keyframes slideIn {
                    from {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                .animate-slide-in {
                    animation: slideIn 0.3s ease-out;
                }
            `}</style>
            {toasts.map((toast) => (
                <ToastItem
                    key={toast.id}
                    id={toast.id}
                    type={toast.type}
                    message={toast.message}
                    onDismiss={() => dismiss(toast.id)}
                />
            ))}
        </div>
    )
}
