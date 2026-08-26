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
            bgColor: 'bg-green-600',
            borderColor: 'border-green-700',
            textColor: 'text-white',
            titleColor: 'text-white',
            icon: CheckCircleIcon,
            iconColor: 'text-white',
            closeButtonClass: 'hover:bg-green-700 text-white/80 hover:text-white',
        },
        error: {
            bgColor: 'bg-red-600',
            borderColor: 'border-red-700',
            textColor: 'text-white',
            titleColor: 'text-white',
            icon: XCircleIcon,
            iconColor: 'text-white',
            closeButtonClass: 'hover:bg-red-700 text-white/80 hover:text-white',
        },
        warning: {
            bgColor: 'bg-amber-600',
            borderColor: 'border-amber-700',
            textColor: 'text-white',
            titleColor: 'text-white',
            icon: ExclamationTriangleIcon,
            iconColor: 'text-white',
            closeButtonClass: 'hover:bg-amber-700 text-white/80 hover:text-white',
        },
        info: {
            bgColor: 'bg-blue-600',
            borderColor: 'border-blue-700',
            textColor: 'text-white',
            titleColor: 'text-white',
            icon: InformationCircleIcon,
            iconColor: 'text-white',
            closeButtonClass: 'hover:bg-blue-700 text-white/80 hover:text-white',
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
            className={`rounded-xl border-0 ${config.bgColor} ${config.borderColor} px-6 py-4 shadow-2xl animate-in fade-in zoom-in-95 duration-300`}
            role="alert"
        >
            <div className="flex items-start gap-4">
                {/* Icon */}
                <IconComponent className={`h-6 w-6 flex-shrink-0 mt-0.5 ${config.iconColor}`} />

                {/* Content */}
                <div className="flex-1 min-w-0">
                    {toast.title && (
                        <h3 className={`font-bold text-base ${config.titleColor}`}>
                            {toast.title}
                        </h3>
                    )}
                    {toast.message && (
                        <p className={`text-sm ${config.textColor} ${toast.title ? 'mt-2' : ''}`}>
                            {toast.message}
                        </p>
                    )}

                    {/* Actions */}
                    {toast.actions && toast.actions.length > 0 && (
                        <div className="mt-4 flex gap-2 flex-wrap">
                            {toast.actions.map((action) => (
                                <a
                                    key={action.label}
                                    href={action.url || '#'}
                                    target={action.newTab ? '_blank' : '_self'}
                                    rel={action.newTab ? 'noopener noreferrer' : ''}
                                    className={`text-sm font-medium ${config.textColor} underline opacity-90 hover:opacity-100 transition`}
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
                        className={`flex-shrink-0 rounded-lg p-1.5 transition-colors ${config.closeButtonClass}`}
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
