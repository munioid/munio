import { usePage } from '@inertiajs/react'
import { route as ziggyRoute } from 'ziggy-js'

export function useRoute() {
    const { props } = usePage()
    const { ziggy } = props

    return (name, params) => {
        return ziggyRoute(name, params, false, ziggy)
    }
}
