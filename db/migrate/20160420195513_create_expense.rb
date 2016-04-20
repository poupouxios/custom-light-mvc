class CreateExpense < ActiveRecord::Migration
  def self.up
    create_table :expenses, options: 'DEFAULT CHARSET=utf8' do |t|
			t.references :user, index: true
      t.float :expense_value
      t.text :comment
      t.timestamps
    end
  end

  def self.down
    drop_table :expenses
  end
end
