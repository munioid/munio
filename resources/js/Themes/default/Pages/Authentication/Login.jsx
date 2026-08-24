import React from 'react'
import { useForm, usePage } from '@inertiajs/react'
import { route } from 'ziggy-js'
import AuthLayout from '../../Layouts/AuthLayout'
import { Button, Input, Alert } from '../../Components/Partial'

export default function Login() {
    const { props } = usePage()
    const { organization } = props

    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
    })

    const handleSubmit = (e) => {
        e.preventDefault()
        post('/login')
    }

    return (
        <AuthLayout>
            <div className="flex flex-col items-center justify-center px-4 py-8">
                <div className="w-full max-w-sm">
                    <div className="text-center mb-8">
                        <h2 className="text-2xl font-bold text-gray-900">
                            Login ke {organization?.name}
                        </h2>
                        <p className="mt-2 text-gray-600">
                            Masukkan kredensial Anda untuk lanjut
                        </p>
                    </div>

                    {errors.email && (
                        <Alert variant="danger" dismissible className="mb-4">
                            {errors.email}
                        </Alert>
                    )}

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <Input
                            type="email"
                            label="Email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            error={errors.email}
                            placeholder="your@email.com"
                            required
                        />

                        <Input
                            type="password"
                            label="Password"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            error={errors.password}
                            placeholder="••••••••"
                            required
                        />

                        <Button
                            type="submit"
                            disabled={processing}
                            loading={processing}
                            className="w-full"
                        >
                            Login
                        </Button>
                    </form>
                </div>
            </div>
        </AuthLayout>
    )
}
