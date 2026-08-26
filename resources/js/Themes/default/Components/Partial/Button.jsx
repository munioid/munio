import React from 'react'

/**
 * Reusable Button Component
 *
 * Props:
 *  - children: button content
 *  - variant: 'primary' | 'secondary' | 'ghost' | 'danger' (default: 'primary')
 *  - size: 'sm' | 'md' | 'lg' (default: 'md')
 *  - disabled: boolean
 *  - loading: boolean
 *  - className: additional tailwind classes
 *  - onClick: click handler
 *  - type: 'button' | 'submit' | 'reset' (default: 'button')
 *  - as: render as different element (Link, etc.)
 */
export default function Button({
    children,
    variant = 'primary',
    size = 'md',
    disabled = false,
    loading = false,
    className = '',
    onClick,
    type = 'button',
    as: Component = 'button',
    ...props
}) {
    const baseClasses = 'font-medium rounded-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2'

    const variantClasses = {
        primary: 'bg-[var(--primary-color)] text-white hover:opacity-90 focus:ring-[var(--primary-color)]',
        secondary: 'bg-gray-200 text-gray-900 hover:bg-gray-300 focus:ring-gray-400',
        ghost: 'border border-[var(--primary-color)] text-[var(--primary-color)] hover:bg-[var(--primary-color)] hover:bg-opacity-10 focus:ring-[var(--primary-color)]',
        danger: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    }

    const sizeClasses = {
        sm: 'px-3 py-1 text-sm',
        md: 'px-4 py-2 text-base',
        lg: 'px-6 py-3 text-lg',
    }

    const finalClassName = `
        ${baseClasses}
        ${variantClasses[variant] || variantClasses.primary}
        ${sizeClasses[size] || sizeClasses.md}
        ${disabled || loading ? 'opacity-50 cursor-not-allowed' : ''}
        ${className}
    `.trim()

    if (Component === 'button') {
        return (
            <button
                type={type}
                disabled={disabled || loading}
                onClick={onClick}
                className={finalClassName}
                {...props}
            >
                {loading ? (
                    <>
                        <span className="inline-block mr-2">
                            <svg className="animate-spin h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        {children}
                    </>
                ) : (
                    children
                )}
            </button>
        )
    }

    return (
        <Component
            className={finalClassName}
            {...props}
        >
            {children}
        </Component>
    )
}
