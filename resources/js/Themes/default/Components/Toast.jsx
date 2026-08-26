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
            className="w-full mx-4 md:mx-0 md:w-full animate-in fade-in zoom-in-95 duration-300"
            style={{
                maxWidth: '650px',
            }}
            role="alert"
        >
            <div
                className="bg-white rounded-[32px] px-9 py-16 shadow-lg"
                style={{
                    boxShadow: '0 4px 12px rgba(0, 0, 0, 0.08)',
                }}
            >
                {/* Icon - Centered at Top with Circle Background */}
                <div className="flex justify-center mb-12">
                    <div
                        className="flex items-center justify-center"
                        style={{
                            width: '104px',
                            height: '104px',
                            borderRadius: '50%',
                            border: `8px solid ${config.iconColor}`,
                            backgroundColor: `${config.iconColor}15`, // 15% opacity
                        }}
                    >
                        <IconComponent
                            className="flex-shrink-0"
                            style={{
                                width: '48px',
                                height: '48px',
                                color: config.iconColor,
                                strokeWidth: 1.5,
                            }}
                        />
                    </div>
                </div>

                {/* Title - Centered */}
                {toast.title && (
                    <h3
                        className="text-center text-black font-black mb-6"
                        style={{
                            fontSize: '48px',
                            lineHeight: '1.1',
                            fontWeight: 900,
                            letterSpacing: '-0.5px',
                        }}
                    >
                        {toast.title}
                    </h3>
                )}

                {/* Message - Centered */}
                {toast.message && (
                    <p
                        className="text-center mb-12"
                        style={{
                            fontSize: '30px',
                            fontWeight: 400,
                            lineHeight: '1.4',
                            color: '#6B6B76',
                        }}
                    >
                        {toast.message}
                    </p>
                )}

                {/* Actions - Centered below message */}
                {toast.actions && toast.actions.length > 0 && (
                    <div className="flex flex-col gap-3 mb-12">
                        {toast.actions.map((action) => (
                            <a
                                key={action.label}
                                href={action.url || '#'}
                                target={action.newTab ? '_blank' : '_self'}
                                rel={action.newTab ? 'noopener noreferrer' : ''}
                                className="text-center font-semibold transition-opacity hover:opacity-75"
                                style={{
                                    fontSize: '18px',
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
                        className="w-full font-bold text-white transition-colors"
                        style={{
                            backgroundColor: config.buttonBg,
                            height: '64px',
                            borderRadius: '28px',
                            fontSize: '28px',
                            fontWeight: 600,
                            border: 'none',
                            cursor: 'pointer',
                            marginTop: '75px',
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
