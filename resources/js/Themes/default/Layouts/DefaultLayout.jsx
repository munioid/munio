import React from 'react'
import AppLayout from './AppLayout'

// DefaultLayout is now an alias for AppLayout to maintain backward compatibility
export default function DefaultLayout({ children }) {
    return <AppLayout>{children}</AppLayout>
}
