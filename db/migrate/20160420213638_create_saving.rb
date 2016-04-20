class CreateSaving < ActiveRecord::Migration
  def self.up
    create_table :savings, options: 'DEFAULT CHARSET=utf8' do |t|
			t.references :user, index: true
      t.float :saving_value
      t.text :comment
      t.timestamps
    end
  end

  def self.down
    drop_table :savings
  end
end
