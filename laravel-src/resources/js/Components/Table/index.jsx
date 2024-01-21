// 内部利用関数
const join = (classNames) => classNames.join(' ')

// テーブル
export const Table = ({children, className = '', header, headerClassName = '', ...props}) => (
    <table className={join(['w-full table text-left bg-white', className])} {...props}>
        {/* テーブルヘッダー */}
        <thead className={headerClassName}>
            {header}
        </thead>
        {/* テーブルメイン */}
        <tbody>
            {children}
        </tbody>
    </table>
)
// 基本的な 標題
export const TH = ({children, className = '', ...props}) => (
    <th className={join(['p-4 font-bold', className])} {...props}>{children}</th>
)
// 基本的な セル
export const TD = ({children, className = '', ...props}) => (
    <td className={join(['p-4 text-gray-600', className])} {...props}>{children}</td>
)
