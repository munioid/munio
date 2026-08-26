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
            textColor: 'text-green-800',
            titleColor: 'text-green-900',
            icon: CheckCircleIcon,
            iconColor: 'text-green-600',
            closeButtonClass: 'hover:bg-green-100 text-green-600',
        },
        error: {
            bgColor: 'bg-red-50',
            borderColor: 'border-red-200',
            textColor: 'text-red-700',
            titleColor: 'text-red-900',
            icon: XCircleIcon,
            iconColor: 'text-red-600',
            closeButtonClass: 'hover:bg-red-100 text-red-600',
        },
        warning: {
            bgColor: 'bg-amber-50',
            borderColor: 'border-amber-200',
            textColor: 'text-amber-700',
            titleColor: 'text-amber-900',
            icon: ExclamationTriangleIcon,
            iconColor: 'text-amber-600',
            closeButtonClass: 'hover:bg-amber-100 text-amber-600',
        },
        info: {
            bgColor: 'bg-blue-50',
            borderColor: 'border-blue-200',
            textColor: 'text-blue-700',
            titleColor: 'text-blue-900',
            icon: InformationCircleIcon,
            iconColor: 'text-blue-600',
            closeButtonClass: 'hover:bg-blue-100 text-blue-600',
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
            className={`mb-3 rounded-lg border ${config.bgColor} ${config.borderColor} p-4 shadow-md animate-in fade-in slide-in-from-top-2 duration-300`}
            role="alert"
        >
            <div className="flex items-start gap-3">
                {/* Icon */}
                <IconComponent className={`h-5 w-5 flex-shrink-0 mt-0.5 ${config.iconColor}`} />

                {/* Content */}
                <div className="flex-1 min-w-0">
                    {toast.title && (
                        <h3 className={`font-semibold ${config.titleColor}`}>
                            {toast.title}
                        </h3>
                    )}
                    {toast.message && (
                        <p className={`text-sm ${config.textColor} ${toast.title ? 'mt-1' : ''}`}>
                            {toast.message}
                        </p>
                    )}

                    {/* Actions */}
                    {toast.actions && toast.actions.length > 0 && (
                        <div className="mt-3 flex gap-2 flex-wrap">
                            {toast.actions.map((action) => (
                                <a
                                    key={action.label}
                                    href={action.url || '#'}
                                    target={action.newTab ? '_blank' : '_self'}
                                    rel={action.newTab ? 'noopener noreferrer' : ''}
                                    className={`text-sm font-medium ${config.textColor} underline hover:opacity-75 transition`}
                                >
                                    {action.label}
                                </a>
                            ))}
                        </div>
                    )}
                </div>

                {/* Close Button */}
                {toast.dismissible !== false && (
                    <button
                        onClick={handleClose}
                        className={`flex-shrink-0 rounded p-1.5 transition-colors ${config.closeButtonClass}`}
                        aria-label="Close notification"
                    >
                        <XMarkIcon className="h-5 w-5" />
                    </button>
                )}
            </div>
        </div>
    )
}

export default Toast
