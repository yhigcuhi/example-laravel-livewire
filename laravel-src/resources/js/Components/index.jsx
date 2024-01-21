// 独自デザイン
export * from './Cards'
export * from './Table'
export * from './Buttons'
/* 集約するやつ(breeze産) */
import ApplicationLogo from './ApplicationLogo'
import NavLink from './NavLink';
import ResponsiveNavLink from './ResponsiveNavLink';
import InputError from './InputError';
import InputLabel from './InputLabel';
import PrimaryButton from './PrimaryButton';
import TextInput from './TextInput';

// 内部利用関数
const join = (classNames) => classNames.join(' ')

/* export breeze産 */
export {
    ApplicationLogo,
    NavLink,
    ResponsiveNavLink,
    InputLabel,
    TextInput,
    InputError,
    PrimaryButton
}

/* export 独自作成部品 */
// メインコンテンツ内 基本 セクション
export const Section = ({children, className = '', ...props}) => (
    <div className={join(['max-w-7xl mx-auto sm:px-6 lg:px-8', className])} {...props}>{children}</div>
);
