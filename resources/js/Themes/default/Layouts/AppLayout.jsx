import React, { useEffect } from 'react'
import { usePage } from '@inertiajs/react'
import Header from '../Components/Header'
import Navigation from '../Components/Navigation'
import ToastContainer from '../Components/ToastContainer'

export default function AppLayout({ children }) {
    const { props } = usePage()
    const { primaryColor } = props

    // Set CSS variable for primary color
    useEffect(() => {
        document.documentElement.style.setProperty('--primary-color', primaryColor)
    }, [primaryColor])

    return (
        <>
            <style>{`
                :root {
                    --primary-color: ${primaryColor};
                }

                ::-webkit-scrollbar {
                    display: none;
                }

                body {
                    background: #f3f4f6;
                }
            `}</style>

            <div className="mx-auto w-full min-h-screen bg-white max-w-[390px] sm:max-w-[430px] md:max-w-[480px] pb-12 relative">
                {/* HEADER */}
                <Header />

                {/* CONTENT */}
                {children}

                {/* TOAST NOTIFICATIONS - Inside mobile container */}
                <ToastContainer />
            </div>

            {/* NAVIGATION */}
            <Navigation />
        </>
    )
}
