import React, { useState, useEffect, useCallback } from 'react'
import { usePage } from '@inertiajs/react'
import Toast from './Toast'

const ToastContainer = () => {
    const { props } = usePage()
    const [toasts, setToasts] = useState([])

    // Initialize toasts from flash data on page load
    useEffect(() => {
        const newToasts = []

        // Handle single toast
        if (props.flash?.toast) {
            newToasts.push(props.flash.toast)
        }

        // Handle multiple toasts
        if (props.flash?.toasts && Array.isArray(props.flash.toasts)) {
            newToasts.push(...props.flash.toasts)
        }

        if (newToasts.length > 0) {
            setToasts(newToasts)
        }
    }, [props.flash])

    const handleRemoveToast = useCallback((toastId) => {
        setToasts((prevToasts) =>
            prevToasts.filter((toast) => toast.id !== toastId)
        )
    }, [])

    if (toasts.length === 0) {
        return null
    }

    return (
        <div
            className="fixed inset-0 z-50 flex items-center justify-center pointer-events-none"
            role="region"
            aria-live="polite"
            aria-atomic="true"
        >
            <div className="pointer-events-auto w-full max-w-sm mx-4 space-y-3">
                {toasts.map((toast) => (
                    <Toast
                        key={toast.id}
                        toast={toast}
                        onClose={handleRemoveToast}
                    />
                ))}
            </div>
        </div>
    )
}

export default ToastContainer
