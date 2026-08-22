import React, { useEffect } from 'react'
import { usePage } from '@inertiajs/react'
import Header from '../Components/Header'

export default function AuthLayout({ children }) {
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

            <div className="mx-auto w-full min-h-screen bg-white max-w-[390px] sm:max-w-[430px] md:max-w-[480px]">
                {/* HEADER */}
                <Header />

                {/* CONTENT */}
                {children}
            </div>
        </>
    )
}
