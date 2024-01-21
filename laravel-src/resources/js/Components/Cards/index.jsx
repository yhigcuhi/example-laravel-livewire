// 内部利用関数
const join = (classNames) => classNames.join(' ')

// カード形式
export const Card = ({children, className = '', title, subTitle, header, ...props}) => (
    // カード Wrapper
    <div className={join(['p-4 sm:p-8 bg-white shadow sm:rounded', className])} {...props}>
        <section>
            {/* カードヘッダー */}
            {
                title
                // タイトル文字存在時
                ? (<CardHeader title={title} subTitle={subTitle}></CardHeader>)
                // その他 ヘッダー要素で描画
                : (<header>{header}</header>)
            }
            {/* カードボディ */}
            {children}
        </section>
    </div>
)
// ベース カードヘッダー
const CardHeader = ({title, subTitle}) => (
    <header>
        {/* 主題 */}
        <h2 className="text-lg font-medium text-gray-900">{ title }</h2>
        {/* 副題 */}
        {subTitle ? (<p className="mt-1 text-sm text-gray-600" v-if="subTitle">{subTitle}</p>) : null}
    </header>
)
