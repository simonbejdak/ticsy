/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./app/Helpers/Fields/Field.php",
        "./app/Helpers/Fields/TablePaginationIndexTextInput.php",
        "./app/Helpers/Fields/TextArea.php",
        "./app/Helpers/Fields/TextInput.php",
        "./app/View/Components/ActivityCard.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
      extend: {
        borderWidth: {
          '3': '3px',
        },
        gridTemplateRows: {
          '7': 'repeat(7, minmax(0, 1fr))',
            '8': 'repeat(8, minmax(0, 1fr))',
            '9': 'repeat(9, minmax(0, 1fr))',
            '10': 'repeat(10, minmax(0, 1fr))',
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
    plugins: [
        require('@tailwindcss/line-clamp'),
    ],
    safelist: [
        'grid-rows-1',
        'grid-rows-2',
        'grid-rows-3',
        'grid-rows-4',
        'grid-rows-5',
        'grid-rows-6',
        'grid-rows-7',
        'grid-rows-8',
        'grid-rows-9',
        'grid-rows-10',
        'grid-rows-11',
        'grid-rows-12',
        'grid-rows-13',
        'grid-rows-14',
        'grid-rows-15',
        'grid-rows-16',
        'grid-rows-17',
        'grid-rows-18',
        'grid-rows-19',
        'grid-rows-20',
    ]
}

