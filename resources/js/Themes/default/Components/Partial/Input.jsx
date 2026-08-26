import React from 'react'

/**
 * Reusable Input Component
 *
 * Props:
 *  - type: 'text' | 'email' | 'password' | 'number' | 'date' | 'search' (default: 'text')
 *  - label: input label
 *  - placeholder: input placeholder
 *  - value: input value
 *  - onChange: change handler
 *  - error: error message
 *  - disabled: boolean
 *  - required: boolean
 *  - className: additional tailwind classes
 *  - helperText: helper text below input
 */
export default function Input({
    type = 'text',
    label,
    placeholder,
    value,
    onChange,
    error,
    disabled = false,
    required = false,
    className = '',
    helperText,
    ...props
}) {
    return (
        <div className="w-full">
            {label && (
                <label className="block text-sm font-medium text-gray-700 mb-1">
                    {label}
                    {required && <span className="text-red-600"> *</span>}
                </label>
            )}

            <input
                type={type}
                value={value}
                onChange={onChange}
                placeholder={placeholder}
                disabled={disabled}
                required={required}
                className={`
                    w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent outline-none transition
                    ${error ? 'border-red-500' : 'border-gray-300'}
                    ${disabled ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'}
                    ${className}
                `.trim()}
                {...props}
            />

            {error && (
                <p className="mt-1 text-sm text-red-600">{error}</p>
            )}

            {helperText && !error && (
                <p className="mt-1 text-sm text-gray-500">{helperText}</p>
            )}
        </div>
    )
}
