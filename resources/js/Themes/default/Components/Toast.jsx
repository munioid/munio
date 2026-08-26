import React, { useState, useEffect } from 'react'
import { XMarkIcon, CheckCircleIcon, ExclamationTriangleIcon, InformationCircleIcon, XCircleIcon } from '@heroicons/react/24/outline'

const Toast = ({ toast, onClose }) => {
    const [isVisible, setIsVisible] = useState(true)

    // Auto-hide after duration
    useEffect(() => {
        if (!toast.duration || toast.duration <= 0) {
            return
        }

        const timer = setTimeout(() => {
            setIsVisible(false)
            onClose(toast.id)
        }, toast.duration)

        return () => clearTimeout(timer)
    }, [toast.duration, toast.id, onClose])

    if (!isVisible) {
        return null
    }

    const typeConfig = {
        success: {
            bgColor: 'bg-green-50',
            borderColor: 'border-green-200',
            textColor: 'text-green-700',
            titleColor: 'text-green-900',
            icon: CheckCircleIcon,
            iconColor: 'text-green-600',
            closeButtonClass: 'hover:bg-green-100 text-green-600 hover:text-green-700',
        },
        error: {
            bgColor: 'bg-red-50',
            borderColor: 'border-red-200',
            textColor: 'text-red-700',
            titleColor: 'text-red-900',
            icon: XCircleIcon,
            iconColor: 'text-red-600',
            closeButtonClass: 'hover:bg-red-100 text-red-600 hover:text-red-700',
        },
        warning: {
            bgColor: 'bg-amber-50',
            borderColor: 'border-amber-200',
            textColor: 'text-amber-700',
            titleColor: 'text-amber-900',
            icon: ExclamationTriangleIcon,
            iconColor: 'text-amber-600',
            closeButtonClass: 'hover:bg-amber-100 text-amber-600 hover:text-amber-700',
        },
        info: {
            bgColor: 'bg-blue-50',
            borderColor: 'border-blue-200',
            textColor: 'text-blue-700',
            titleColor: 'text-blue-900',
            icon: InformationCircleIcon,
            iconColor: 'text-blue-600',
            closeButtonClass: 'hover:bg-blue-100 text-blue-600 hover:text-blue-700',
        },
    }

    const config = typeConfig[toast.type] || typeConfig.info
    const IconComponent = config.icon

    const handleClose = () => {
        setIsVisible(false)
        onClose(toast.id)
    }

    return (
        <div
            className={`w-full max-w-xs rounded-2xl border-2 ${config.bgColor} ${config.borderColor} px-8 py-8 shadow-2xl animate-in fade-in zoom-in-95 duration-300`}
            role="alert"
        >
            {/* Icon - Centered at Top */}
            <div className="flex justify-center mb-4">
                <IconComponent className={`h-12 w-12 ${config.iconColor}`} />
            </div>

            {/* Title - Centered */}
            {toast.title && (
                <h3 className={`text-center font-bold text-lg ${config.titleColor} mb-2`}>
                    {toast.title}
                </h3>
            )}

            {/* Message - Centered */}
            {toast.message && (
                <p className={`text-center text-sm ${config.textColor} mb-6`}>
                    {toast.message}
                </p>
            )}

            {/* Actions - Centered below message */}
            {toast.actions && toast.actions.length > 0 && (
                <div className="flex flex-col gap-3 mb-6">
                    {toast.actions.map((action) => (
                        <a
                            key={action.label}
                            href={action.url || '#'}
                            target={action.newTab ? '_blank' : '_self'}
                            rel={action.newTab ? 'noopener noreferrer' : ''}
                            className={`text-center text-sm font-medium ${config.textColor} underline opacity-90 hover:opacity-100 transition`}
                        >
                            {action.label}
                        </a>
                    ))}
                </div>
            )}

            {/* Close Button - Centered at Bottom */}
            {toast.dismissible !== false && (
                <div className="flex justify-center">
                    <button
                        onClick={handleClose}
                        className={`px-6 py-2 rounded-lg font-medium text-sm transition-colors ${config.closeButtonClass} border border-current`}
                        aria-label="Close notification"
                    >
                        Close
                    </button>
                </div>
            )}
        </div>
    )
}

export default Toast
