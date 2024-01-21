/* import inertia */
import { Link } from '@inertiajs/react';

/**
 * @return 追加ボタン
 */
export default function AppendButton({ className = '', href, children, ...props }) {
    return (
        <Link
            className={[
                'inline-flex items-center px-4 py-2 bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
                , className
            ].join(' ')}
            href={href}
            {...props}
        >
            <div className='flex items-center justify-between'>
                {children ?? '追加'}
            </div>
        </Link>
    )
}
