import React from 'react'
import { Head, usePage } from '@inertiajs/react'

export default function PageHead({ title }) {
    const { props } = usePage()
    const { organization, favicon } = props
    const orgName = organization?.name

    const fullTitle = title ? (orgName ? `${title} | ${orgName}` : title) : orgName

    return (
        <Head>
            {fullTitle && <title>{fullTitle}</title>}
            {favicon && <link rel="icon" href={favicon} />}
        </Head>
    )
}
