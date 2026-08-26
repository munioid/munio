import React, { createContext, useState, useCallback } from 'react'

export const ToastContext = createContext()

export function ToastProvider({ children, flashes = {} }) {
    const [toasts, setToasts] = useState([])

    // Convert flash messages to toasts
    React.useEffect(() => {
        const flashToasts = []

        if (flashes.success) {
            flashToasts.push({
                id: `success-${Date.now()}`,
                type: 'success',
                message: flashes.success,
            })
        }

        if (flashes.error) {
            flashToasts.push({
                id: `error-${Date.now()}`,
                type: 'error',
                message: flashes.error,
            })
        }

        if (flashes.warning) {
            flashToasts.push({
                id: `warning-${Date.now()}`,
                type: 'warning',
                message: flashes.warning,
            })
        }

        if (flashes.info) {
            flashToasts.push({
                id: `info-${Date.now()}`,
                type: 'info',
                message: flashes.info,
            })
        }

        if (flashToasts.length > 0) {
            setToasts((prev) => [...prev, ...flashToasts])
        }
    }, [flashes])

    const show = useCallback((message, options = {}) => {
        const {
            type = 'info',
            duration = 5000,
        } = options

        const id = `toast-${Date.now()}-${Math.random()}`
        const toast = { id, type, message }

        setToasts((prev) => [...prev, toast])

        if (duration > 0) {
            setTimeout(() => {
                dismiss(id)
            }, duration)
        }

        return id
    }, [])

    const dismiss = useCallback((id) => {
        setToasts((prev) => prev.filter((toast) => toast.id !== id))
    }, [])

    const success = useCallback((message, options = {}) => {
        return show(message, { ...options, type: 'success' })
    }, [show])

    const error = useCallback((message, options = {}) => {
        return show(message, { ...options, type: 'error' })
    }, [show])

    const warning = useCallback((message, options = {}) => {
        return show(message, { ...options, type: 'warning' })
    }, [show])

    const info = useCallback((message, options = {}) => {
        return show(message, { ...options, type: 'info' })
    }, [show])

    return (
        <ToastContext.Provider
            value={{
                toasts,
                show,
                dismiss,
                success,
                error,
                warning,
                info,
            }}
        >
            {children}
        </ToastContext.Provider>
    )
}
