import React from 'react'
import { usePage } from '@inertiajs/react'
import AppLayout from '../Layouts/AppLayout'
import PostSlider from '../Components/Home/PostSlider'
import EventSlider from '../Components/Home/EventSlider'

export default function Home() {
    const { props } = usePage()
    const { primaryColor, posts = [], events = [] } = props

    return (
        <AppLayout title="Home">
            <div>
                <PostSlider posts={posts} primaryColor={primaryColor} />
                <EventSlider events={events} primaryColor={primaryColor} />
            </div>
        </AppLayout>
    )
}
