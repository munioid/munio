import React, { useState, useEffect } from "react";
import {
    CheckCircleIcon,
    ExclamationTriangleIcon,
    InformationCircleIcon,
    XCircleIcon,
} from "@heroicons/react/24/outline";

const Toast = ({ toast, onClose }) => {
    const [isVisible, setIsVisible] = useState(true);

    // Auto-hide after duration
    useEffect(() => {
        if (!toast.duration || toast.duration <= 0) {
            return;
        }

        const timer = setTimeout(() => {
            setIsVisible(false);
            onClose(toast.id);
        }, toast.duration);

        return () => clearTimeout(timer);
    }, [toast.duration, toast.id, onClose]);

    if (!isVisible) {
        return null;
    }

    const typeConfig = {
        success: {
            icon: CheckCircleIcon,
            iconColor: "#10B981",
            buttonBg: "#FF5C54",
            buttonHover: "#E64A42",
        },
        error: {
            icon: XCircleIcon,
            iconColor: "#EF4444",
            buttonBg: "#EF4444",
            buttonHover: "#DC2626",
        },
        warning: {
            icon: ExclamationTriangleIcon,
            iconColor: "#F59E0B",
            buttonBg: "#F59E0B",
            buttonHover: "#D97706",
        },
        info: {
            icon: InformationCircleIcon,
            iconColor: "#3B82F6",
            buttonBg: "#3B82F6",
            buttonHover: "#2563EB",
        },
    };

    const config = typeConfig[toast.type] || typeConfig.info;
    const IconComponent = config.icon;

    const handleClose = () => {
        setIsVisible(false);
        onClose(toast.id);
    };

    return (
        <div
            className="animate-in fade-in zoom-in-95 duration-300"

            role="alert"
        >
            <div
                className="bg-white rounded-[28px] px-10 py-12 shadow-lg flex flex-col items-center text-center fi-no-notification"
                style={{
                    boxShadow: "0 2px 8px rgba(0, 0, 0, 0.08)",
                }}
            >
                {/* Icon - Centered at Top with Circle Background */}
                {true && (
                    <div
                        className="flex items-center justify-center flex-shrink-0"
                    >
                        <IconComponent
                            className="fi-no-notification-icon"
                            style={{
                                color: config.iconColor,
                                strokeWidth: 1.5,
                            }}
                        />
                    </div>
                )}

                {/* Title - Centered */}
                {toast.title && (
                    <h3
                        className="fi-no-notification-title"
                        // style={{
                        //     fontSize: '40px',
                        //     lineHeight: '1.2',
                        //     fontWeight: 700,
                        //     marginBottom: '24px',
                        // }}
                    >
                        {toast.title}
                    </h3>
                )}

                {/* Message - Centered */}
                {toast.message && (
                    <p
                        className="fi-no-notification-body"
                        // style={{
                        //     fontSize: '22px',
                        //     fontWeight: 400,
                        //     lineHeight: '1.4',
                        //     color: '#6B6B76',
                        //     marginBottom: '48px',
                        // }}
                    >
                        {toast.message}
                    </p>
                )}

                {/* Actions - Centered below message */}
                {toast.actions && toast.actions.length > 0 && (
                    <div className="fi-no-notification-actions">
                        {toast.actions.map((action) => (
                            <a
                                key={action.label}
                                href={action.url || "#"}
                                target={action.newTab ? "_blank" : "_self"}
                                rel={action.newTab ? "noopener noreferrer" : ""}
                                className="font-semibold transition-opacity hover:opacity-75"
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
                        className="font-bold text-white transition-colors w-full fi-no-notification-actions"
                        style={{
                            backgroundColor: config.buttonBg,
                        }}
                        onMouseEnter={(e) =>
                            (e.target.style.backgroundColor =
                                config.buttonHover)
                        }
                        onMouseLeave={(e) =>
                            (e.target.style.backgroundColor = config.buttonBg)
                        }
                        aria-label="Close notification"
                    >
                        {toast.dismissText || "Tutup"}
                    </button>
                )}
            </div>
        </div>
    );
};

export default Toast;
