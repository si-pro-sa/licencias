describe('The Login Page', () => {
    it('successfully loads', () => {
        cy.visit('http://localhost:8000') // change URL to match your dev URL
    })
})
describe('The Login Page through the conf', () => {
    it('successfully loads', () => {
        cy.visit('/')
    })
})
