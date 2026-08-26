import React, { useState, useEffect } from 'react'
import { CheckCircleIcon, ExclamationTriangleIcon, InformationCircleIcon, XCircleIcon } from '@heroicons/react/24/outline'

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
            icon: CheckCircleIcon,
            iconColor: '#10B981',
            buttonBg: '#FF5C54',
            buttonHover: '#E64A42',
        },
        error: {
            icon: XCircleIcon,
            iconColor: '#EF4444',
            buttonBg: '#EF4444',
            buttonHover: '#DC2626',
        },
        warning: {
            icon: ExclamationTriangleIcon,
            iconColor: '#F59E0B',
            buttonBg: '#F59E0B',
            buttonHover: '#D97706',
        },
        info: {
            icon: InformationCircleIcon,
            iconColor: '#3B82F6',
            buttonBg: '#3B82F6',
            buttonHover: '#2563EB',
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
            className="animate-in fade-in zoom-in-95 duration-300"
            style={{
                width: 'calc(100% - 40px)',
                maxWidth: '600px',
            }}
            role="alert"
        >
            <div
                className="bg-white rounded-[28px] px-10 py-12 shadow-lg flex flex-col items-center text-center"
                style={{
                    boxShadow: '0 2px 8px rgba(0, 0, 0, 0.08)',
                }}
            >
                {/* Icon - Centered at Top with Circle Background */}
                {true && (
                    <div
                        className="flex items-center justify-center flex-shrink-0"
                        style={{
                            width: '100px',
                            height: '100px',
                            borderRadius: '50%',
                            border: `7px solid ${config.iconColor}`,
                            backgroundColor: `${config.iconColor}15`,
                            marginBottom: '48px',
                        }}
                    >
                        <IconComponent
                            style={{
                                width: '48px',
                                height: '48px',
                                color: config.iconColor,
                                strokeWidth: 1.5,
                            }}
                        />
                    </div>
                )}

                {/* Title - Centered */}
                {toast.title && (
                    <h3
                        className="text-black font-bold"
                        style={{
                            fontSize: '40px',
                            lineHeight: '1.2',
                            fontWeight: 700,
                            marginBottom: '24px',
                        }}
                    >
                        {toast.title}
                    </h3>
                )}

                {/* Message - Centered */}
                {toast.message && (
                    <p
                        style={{
                            fontSize: '22px',
                            fontWeight: 400,
                            lineHeight: '1.4',
                            color: '#6B6B76',
                            marginBottom: '48px',
                        }}
                    >
                        {toast.message}
                    </p>
                )}

                {/* Actions - Centered below message */}
                {toast.actions && toast.actions.length > 0 && (
                    <div className="flex flex-col gap-2 mb-8 w-full">
                        {toast.actions.map((action) => (
                            <a
                                key={action.label}
                                href={action.url || '#'}
                                target={action.newTab ? '_blank' : '_self'}
                                rel={action.newTab ? 'noopener noreferrer' : ''}
                                className="font-semibold transition-opacity hover:opacity-75"
                                style={{
                                    fontSize: '16px',
                                    color: config.iconColor,
                                    textDecoration: 'none',
                                }}
                            >
                                {action.label}
                            </a>
                        ))}
                    </div>
                )}

                {/* Close Button - Full Width at Bottom */}
                {toast.dismissible !== false && (
                    <button
                        onClick={handleClose}
                        className="font-bold text-white transition-colors w-full"
                        style={{
                            backgroundColor: config.buttonBg,
                            height: '60px',
                            borderRadius: '24px',
                            fontSize: '18px',
                            fontWeight: 600,
                            border: 'none',
                            cursor: 'pointer',
                            marginTop: '32px',
                        }}
                        onMouseEnter={(e) => (e.target.style.backgroundColor = config.buttonHover)}
                        onMouseLeave={(e) => (e.target.style.backgroundColor = config.buttonBg)}
                        aria-label="Close notification"
                    >
                        {toast.dismissText || 'Tutup'}
                    </button>
                )}
            </div>
        </div>
    )
}

export default Toast
