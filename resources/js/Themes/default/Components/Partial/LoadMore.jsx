import React from 'react'

/**
 * Reusable LoadMore Component
 *
 * Props:
 *  - onClick: load more handler
 *  - loading: boolean (show loading state)
 *  - disabled: boolean
 *  - label: button label (default: 'Muat Lebih Banyak')
 *  - loadingLabel: label while loading (default: 'Memuat...')
 *  - className: additional tailwind classes
 */
export default function LoadMore({
    onClick,
    loading = false,
    disabled = false,
    label = 'Muat Lebih Banyak',
    loadingLabel = 'Memuat...',
    className = '',
}) {
    return (
        <div className="px-5">
            <button
                onClick={onClick}
                disabled={disabled || loading}
                className={`
                    w-full rounded-xl border border-[var(--primary-color)] py-3 text-[var(--primary-color)] font-medium
                    hover:bg-[var(--primary-color)] hover:text-white transition
                    disabled:opacity-50 disabled:cursor-not-allowed
                    ${className}
                `.trim()}
            >
                {loading ? (
                    <>
                        <span className="inline-block mr-2">
                            <svg className="animate-spin h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        {loadingLabel}
                    </>
                ) : (
                    label
                )}
            </button>
        </div>
    )
}
