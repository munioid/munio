import React, { useEffect } from 'react'

/**
 * Reusable Modal Component
 *
 * Props:
 *  - isOpen: boolean
 *  - onClose: close handler
 *  - title: modal title
 *  - children: modal content
 *  - footer: footer content (usually buttons)
 *  - size: 'sm' | 'md' | 'lg' | 'xl' (default: 'md')
 *  - closeButton: show close button (default: true)
 *  - className: additional tailwind classes
 */
export default function Modal({
    isOpen,
    onClose,
    title,
    children,
    footer,
    size = 'md',
    closeButton = true,
    className = '',
}) {
    useEffect(() => {
        if (isOpen) {
            document.body.style.overflow = 'hidden'
        } else {
            document.body.style.overflow = 'unset'
        }

        return () => {
            document.body.style.overflow = 'unset'
        }
    }, [isOpen])

    if (!isOpen) return null

    const sizeClasses = {
        sm: 'max-w-sm',
        md: 'max-w-md',
        lg: 'max-w-lg',
        xl: 'max-w-xl',
    }

    return (
        <>
            {/* Backdrop */}
            <div
                className="fixed inset-0 bg-black bg-opacity-50 z-40 transition"
                onClick={onClose}
            />

            {/* Modal */}
            <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div
                    className={`
                        bg-white rounded-lg shadow-lg w-full
                        ${sizeClasses[size] || sizeClasses.md}
                        ${className}
                    `.trim()}
                    onClick={(e) => e.stopPropagation()}
                >
                    {/* Header */}
                    {title && (
                        <div className="flex items-center justify-between p-6 border-b">
                            <h2 className="text-lg font-semibold text-gray-900">
                                {title}
                            </h2>
                            {closeButton && (
                                <button
                                    onClick={onClose}
                                    className="text-gray-500 hover:text-gray-700 transition"
                                >
                                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            )}
                        </div>
                    )}

                    {/* Content */}
                    <div className="p-6">
                        {children}
                    </div>

                    {/* Footer */}
                    {footer && (
                        <div className="p-6 border-t bg-gray-50 rounded-b-lg">
                            {footer}
                        </div>
                    )}
                </div>
            </div>
        </>
    )
}
