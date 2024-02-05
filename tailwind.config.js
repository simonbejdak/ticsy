/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./app/Helpers/Fields/Field.php",
        "./app/Helpers/Fields/TablePaginationIndexTextInput.php",
        "./app/Helpers/Fields/TextInput.php",
        "./app/View/Components/ActivityCard.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {

      fontFamily: {
        'sans': ['Oswald', 'system-ui'],
      },
      extend: {
        borderWidth: {
          '3': '3px',
        },
      gridTemplateRows: {
          '7': 'repeat(7, minmax(0, 1fr))',
        },
        width: {
          '2.5': '10rem',
        },
        scale: {
          '101': '1.01',
        },
        fontSize: {
          'xxs': '0.65rem',
        },
      }
    },
    variants: {
        extend: {
            display: ["group-hover"],
        },
    },
    plugins: [],
}

