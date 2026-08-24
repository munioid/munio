import React from 'react'

/**
 * Reusable Badge Component
 *
 * Props:
 *  - children: badge content
 *  - variant: 'primary' | 'secondary' | 'success' | 'warning' | 'danger' | 'info' (default: 'primary')
 *  - size: 'sm' | 'md' | 'lg' (default: 'md')
 *  - className: additional tailwind classes
 */
export default function Badge({
    children,
    variant = 'primary',
    size = 'md',
    className = '',
}) {
    const variantClasses = {
        primary: 'bg-[var(--primary-color)] text-white',
        secondary: 'bg-gray-200 text-gray-900',
        success: 'bg-green-100 text-green-800',
        warning: 'bg-yellow-100 text-yellow-800',
        danger: 'bg-red-100 text-red-800',
        info: 'bg-blue-100 text-blue-800',
    }

    const sizeClasses = {
        sm: 'px-2 py-0.5 text-xs font-semibold',
        md: 'px-3 py-1 text-sm font-semibold',
        lg: 'px-4 py-2 text-base font-semibold',
    }

    return (
        <span className={`
            rounded-full inline-block
            ${variantClasses[variant] || variantClasses.primary}
            ${sizeClasses[size] || sizeClasses.md}
            ${className}
        `.trim()}>
            {children}
        </span>
    )
}
